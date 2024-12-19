<?php

namespace Packages\PageEditor;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Packages\Basic\Models\Media;
use Packages\PageEditor\Models\Page;
use Packages\PageEditor\Models\ShareWidget;
use Packages\PageEditor\Models\Widget;
use Spatie\MediaLibrary\MediaCollections\FileAdderFactory;
use Spatie\MediaLibraryPro\Dto\PendingMediaItem;


class PageEditor
{
    /**
     * Generate frontend dynamic route
     */
    public function router()
    {
        return Route::group([], function () {
            Route::get('/', '\Packages\PageEditor\Http\Controllers\PageController@page');
            Route::get('{any}', '\Packages\PageEditor\Http\Controllers\PageController@page')->where('any', '.*');
        });
    }

    /**
     * Get page components
     */
    public function getPageComponents($id)
    {
        $widgets = Widget::where('model_type', Page::class)->where('model_id', $id)->orderBy('ranking')->get();

        return $widgets->transform(function ($item) {
            $config = config("page-editor-components." . data_get($item, 'data.key'), []);

            Arr::set($config, 'fields', array_map(fn($field) => [
                ...$field,
                'value' => data_get($item, 'data.data.' . $field['name'])
            ], data_get($config, 'fields', [])));

            //share widget
            if (data_get($item, 'data.type') == 'share_widget') {
                $shareWidget = ShareWidget::where('uuid', data_get($item, 'data.key'))->first();
                $config = [
                    'key' => data_get($shareWidget, 'uuid'),
                    'label' => data_get($shareWidget, 'name'),
                    'type' => 'share_widget',
                    'component' => data_get($shareWidget, 'component_key'),
                    'id' => data_get($shareWidget, 'id'),
                    'callback' => [
                        [
                            'name' => 'props',
                            'class' => 'Packages\PageEditor\Http\Controllers\NovaShareWidgetController',
                            'function' => 'data',
                            'args' => [
                                'id'
                            ]
                        ]
                    ]
                ];
            }

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

            return array_merge($config, Arr::only(data_get($item, 'data', []), ['uuid']));
        });
    }

    public function getComponentList()
    {
        //component list
        $componentList = config('page-editor.components', []);
        //add share widget
        array_push($componentList, [
            'key' => 'share_widget',
            'label' => 'Share Widget',
            'type' => 'share_widget',
            'components' => ShareWidget::where('system', 0)->where('enabled', 1)->get()->transform(function ($shareWidget) {
                return [
                    'key' => data_get($shareWidget, 'uuid'),
                    'label' => data_get($shareWidget, 'name'),
                    'type' => 'share_widget',
                    'component' => data_get($shareWidget, 'component_key'),
                    'id' => data_get($shareWidget, 'id'),
                    'callback' => [
                        [
                            'name' => 'props',
                            'class' => 'Packages\PageEditor\Http\Controllers\NovaShareWidgetController',
                            'function' => 'data',
                            'args' => [
                                'id'
                            ]
                        ]
                    ]
                ];
            })
        ]);

        return $componentList;
    }

    /**
     * handle widget save media file 
     */
    public function handleMediaLibrary($data, $widget, $rawData)
    {
        return Arr::map((array) $data, function ($value, $key) use ($widget, $rawData) {
            // delete media
            $medias = Media::where('model_type', Widget::class)
                ->where('model_id', $widget->id)
                ->where('collection_name', $key)
                ->get();

            if ($medias) {
                foreach ($medias as $media) {
                    if (!Str::contains($rawData, $media->uuid)) {
                        $media->delete();
                    }
                }
            }

            if (data_get($value, 'extension') && data_get($value, 'size')) {
                if (Media::where('uuid', data_get($value, 'uuid'))->where('model_type', '!=', Widget::class)->first()) {
                    $customProperties = (array) data_get($value, 'custom_properties', []);
                    $pendingMediaItem = new PendingMediaItem(
                        data_get($value, 'uuid'),
                        data_get($value, 'name'),
                        data_get($value, 'order'),
                        $customProperties,
                        []
                    );

                    $media = app(FileAdderFactory::class)
                        ->createForPendingMedia($widget, $pendingMediaItem)
                        ->toMediaCollection($key, '');

                    data_set($value, 'preview_url', $media->getUrl('preview'));
                    data_set($value, 'original_url', $media->getUrl());
                }

                return $value;
            }

            if (!is_object($value) && !is_array($value)) {
                return $value;
            }

            return $this->handleMediaLibrary($value, $widget, $rawData);
        });

        return $data;
    }
}
