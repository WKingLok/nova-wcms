<?php

namespace Packages\PageEditor\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Laravel\Nova\Menu\Breadcrumb;
use Laravel\Nova\Menu\Breadcrumbs;
use Laravel\Nova\Nova;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Packages\PageEditor\Facades\PageEditor;
use Packages\PageEditor\Models\Page;
use Packages\PageEditor\Models\ShareWidget;
use Packages\PageEditor\Models\Widget;

class NovaShareWidgetController extends Controller
{
    /**
     * Show Resource Update Attached page using Inertia.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Inertia\Response
     */
    public function __invoke(NovaRequest $request)
    {
        $resourceClass = $request->resource();

        $isPolymorphic = function ($query) {
            return is_null($query) || in_array($query, [true, 1, '1']);
        };

        $parentResource = $request->findResourceOrFail();

        Gate::authorize('editor', $parentResource->resource);

        $config = config("page-editor-components." . data_get($parentResource, 'component_key'), []);

        $componentData = Widget::where('model_type', ShareWidget::class)->where('model_id', $parentResource->id)->orderBy('ranking')->first();

        $config['fields'] = array_map(function ($field) use ($componentData) {
            return [
                ...$field,
                'value' => data_get($componentData, 'data.data.' . $field['name'])
            ];
        }, $config['fields']);

        //callback
        if ($callbacks = data_get($config, 'callback')) {
            $config['callback'] = array_map(function ($callback) use ($config) {
                $class = app(data_get($callback, 'class'));
                $func = data_get($callback, 'function');
                $args = Arr::mapWithKeys(data_get($callback, 'args', []), function ($key, $index) use ($config) {
                    return [$key => data_get($config, $key)];
                });
                $request = request()->merge($args);
                $data = $class->$func($request);

                if (is_a($data, 'Illuminate\Http\JsonResponse')) {
                    $data = json_decode($data->content(), true);
                }

                return [
                    ...$callback,
                    'value' => $data
                ];
            }, $callbacks);
        }

        $data = [array_merge($config, Arr::only(data_get($componentData, 'data', []), ['uuid']))];

        //component list
        $componentList = [];

        //preview config
        $preview = [
            ...config('page-editor.preview', []),
            'type' => 'share-widget',
        ];

        return Inertia::render('PageEditor.Editor', [
            'breadcrumbs' => $this->breadcrumbs($request),
            'resourceName' => $resourceClass::uriKey(),
            'resourceId' => $request->resourceId,
            'relatedResourceName' => $request->relatedResource,
            'relatedResourceId' => $request->relatedResourceId,
            'viaRelationship' => $request->query('viaRelationship'),
            'viaPivotId' => $request->query('viaPivotId'),
            'polymorphic' => $isPolymorphic($request->query('polymorphic')),
            'viaResource' => $parentResource::uriKey(),
            'viaResourceId' => $parentResource->resource->getKey(),
            'parentResource' => [
                'name' => $parentResource->singularLabel(),
                'display' => $parentResource->title(),
            ],
            //custom
            'locales' => config('translatable.locales_with_label'),
            'preview' => $preview,
            'data' => $data,
            'componentList' => $componentList,
            'csrfToken' => csrf_token(),
        ]);
    }

    /**
     * Store
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Inertia\Response
     */
    public function update(Request $request, $id)
    {
        try {
            Gate::authorize('update', ShareWidget::find($id)->first());

            $data = $request->data;
            $rawData = json_encode($data);

            foreach ($data as $index => $item) {
                $widget = Widget::updateOrCreate(
                    [
                        'uuid' => data_get($item, 'uuid'),
                        'model_type' => ShareWidget::class,
                        'model_id' => $id,
                    ],
                    [
                        'ranking' => $index,
                        'data' => $item,
                        'is_global' => false,
                    ]
                );

                data_set($item, 'data', PageEditor::handleMediaLibrary(data_get($item, 'data'), $widget, $rawData));

                $widget->update([
                    'data' => $item
                ]);
            }
        } catch (AuthorizationException $th) {
            return response()->json([
                'message' => 'This action is unauthorized.',
            ], 403);
        }
    }

    /**
     * Get breadcrumb menu for the page.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Laravel\Nova\Menu\Breadcrumbs
     */
    protected function breadcrumbs(NovaRequest $request)
    {
        $resourceClass = $request->resource();
        $resource = $request->findResourceOrFail();

        return Breadcrumbs::make(
            collect([Breadcrumb::make(Nova::__('Resources'))])->when($request->viaRelationship(), function ($breadcrumbs) use ($request) {
                return $breadcrumbs->push(
                    Breadcrumb::resource($request->viaResource()),
                    Breadcrumb::resource($request->findParentResource())
                );
            }, function ($breadcrumbs) use ($resourceClass, $resource) {
                return $breadcrumbs->push(
                    Breadcrumb::resource($resourceClass),
                    Breadcrumb::resource($resource),
                );
            })->push(
                Breadcrumb::make(__('Editor'))
            )->all()
        );
    }


    public function widgetCallback(Request $request)
    {
        $config = $request->config;
        $data = $request->data;
        $class = app(data_get($config, 'class'));
        $func = data_get($config, 'function');
        $args = Arr::mapWithKeys(data_get($config, 'args', []), function ($key, $index) use ($data) {
            return [$key => data_get($data, $key)];
        });

        $request = request()->merge($args);
        $data = $class->$func($request);

        if (is_a($data, 'Illuminate\Http\JsonResponse')) {
            $data = json_decode($data->content(), true);
        }

        return response()->json($data);
    }

    public function data(Request $request)
    {
        try {
            $shareWidget = ShareWidget::where('id', $request->id)->first();
            $widget = $shareWidget->widget->first();
            $config = config("page-editor-components." . data_get($shareWidget, 'component_key'), []);
            $data = data_get($widget, 'data', []);

            //callback
            if ($callbacks = data_get($config, 'callback')) {
                foreach ($callbacks as $callback) {
                    $class = app(data_get($callback, 'class'));
                    $func = data_get($callback, 'function');
                    $args = Arr::mapWithKeys(data_get($callback, 'args', []), function ($key, $index) use ($config) {
                        return [$key => data_get($config, $key)];
                    });
                    $request = request()->merge($args);
                    $callback_data = $class->$func($request);

                    if (is_a($callback_data, 'Illuminate\Http\JsonResponse')) {
                        $callback_data = json_decode($callback_data->content(), true);
                    }

                    if (data_get($callback, 'name') == "props") {
                        Arr::set($data, 'data', [
                            ...data_get($data, 'data', []),
                            ...data_get($callback_data, 'data', [])
                        ]);
                    } else {
                        Arr::set($data, 'data.' . data_get($callback, 'name'), data_get($callback_data, 'data'));
                    }
                }
            }

            return Arr::except($data, ['uuid', 'key', 'type']);
        } catch (\Throwable $th) {
            return null;
        }
    }
}
