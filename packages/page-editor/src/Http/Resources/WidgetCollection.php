<?php

namespace Packages\PageEditor\Http\Resources;

use Packages\PageEditor\Models\ShareWidget;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * Class WidgetCollection
 * @package Pacjage\PageEditor\Http\Resources
 */
class WidgetCollection extends JsonResource
{
    public $medias;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {

        $widget = $this->data;
        $data = data_get($widget, 'data', []);
        $this->medias = $this->resource->media;
        $config = config("page-editor-components." . data_get($this, 'data.key'), []);

        //share widget
        if (data_get($widget, 'type') == 'share_widget') {
            $shareWidget = ShareWidget::where('uuid', data_get($widget, 'key'))->first();
            $this->medias = data_get($shareWidget->widget->first(), 'media', collect());

            $config = [
                'key' => data_get($shareWidget, 'component_key'),
                'type' => 'share_widget',
                'id' => data_get($shareWidget, 'id'),
                'callback' => [
                    [
                        'name' => 'props',
                        'class' => 'Packages\PageEditor\Http\Controllers\NovaShareWidgetController',
                        'function' => 'data',
                        'args' => ['id']
                    ]
                ]
            ];

            Arr::set($widget, 'key', data_get($shareWidget, 'component_key'));
        }

        //callback
        if ($callbacks = data_get($config, 'callback')) {
            foreach ($callbacks as $callback) {
                $class = app(data_get($callback, 'class'));
                $func = data_get($callback, 'function');
                $args = Arr::mapWithKeys(data_get($callback, 'args', []), function ($key, $index) use ($config) {
                    return [$key => data_get($config, $key)];
                });
                $key = data_get($callback, 'name');
                $request->merge($args);
                $func_data = $class->$func($request);

                if (is_a($func_data, 'Illuminate\Http\JsonResponse')) {
                    $func_data = json_decode($func_data->content(), true);
                }

                if ($key == "props") {
                    $data = array_merge($data, data_get($func_data, 'data', []));
                } else {
                    $data[data_get($callback, 'name')] = data_get($func_data, 'data', $func_data);
                }
            }
        }

        Arr::set($widget, 'data', Arr::map($data, function ($data) {
            return $this->dataTransform($data);
        }));

        return $widget;
    }

    private function dataTransform($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        //translatable
        if (Arr::where(config('translatable.locales'), fn($locale) => in_array($locale, array_keys($data)))) {
            return $this->dataTransform(data_get($data, app()->getLocale()));
        }

        //image
        if (data_get($data, 'extension') && data_get($data, 'size')) {
            if ($media = $this->medias->where('uuid', data_get($data, 'uuid'))->first()) {
                return [
                    'path' =>  $media->hasGeneratedConversion('conversion') ? $media->getUrl('conversion') : $media->getUrl(),
                    'alt' => $media->getCustomProperty('alt', $media->file_name),
                ];
            }

            return null;
        }

        return Arr::map($data, function ($data) {
            return $this->dataTransform($data);
        });
    }
}
