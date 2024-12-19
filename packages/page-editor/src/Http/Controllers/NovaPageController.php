<?php

namespace Packages\PageEditor\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
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
use Packages\PageEditor\Models\Widget;

class NovaPageController extends Controller
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

        $data = PageEditor::getPageComponents($request->resourceId);

        //component list
        $componentList = PageEditor::getComponentList();

        //preview config
        $preview = [
            ...config('page-editor.preview', []),
            'type' => 'page',
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
            'tinymceConfig' => config('page-editor.tinymce'),
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
            Gate::authorize('update', Page::find($id)->first());

            $data = $request->data;
            $rawData = json_encode($data);

            /**
             * delete widgets that are not in the request
             */
            Widget::where('model_type', Page::class)->where('model_id', $id)->whereNotIn('uuid', Arr::pluck($data, 'uuid'))->delete();

            foreach ($data as $index => $item) {
                $widget = Widget::updateOrCreate(
                    [
                        'uuid' => data_get($item, 'uuid'),
                        'model_type' => Page::class,
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
}
