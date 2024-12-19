<?php

namespace Packages\MediaFiles;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class MediaFiles extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'media-files';

    /**
     * On save callback function
     *
     * @var function
     */
    public $mediaCallback;

    /**
     * Set the file max size.
     *
     * @param  int  $size
     * @return $this
     */
    public function maxSize(int $size)
    {
        return $this->withMeta(['maxSize' => $size]);
    }

    /**
     * Set the file accept type.
     *
     * @param  array  $accept
     * @return $this
     */
    public function acceptType(array $accept)
    {
        return $this->withMeta(['accept' => $accept]);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     */
    public function fillInto(NovaRequest $request, $model, $attribute, $requestAttribute = null)
    {
        if (!$model->id) {
            $model->fill($request->all());
            $model->save();
        }

        if (isset($this->mediaCallback)) {
            call_user_func($this->mediaCallback, json_decode($request->$attribute, true), $model, $attribute);
        }
        return null;
    }

    /**
     * override functrion
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        $this->resource = $resource;

        $attribute = $attribute ?? $this->attribute;

        if ($attribute === 'ComputedField') {
            $this->value = call_user_func($this->computedCallback, $resource);

            return;
        }

        if (!$this->resolveCallback) {
            $this->value = $resource->getMedia($attribute);
        } elseif (is_callable($this->resolveCallback)) {
            tap($this->resolveAttribute($resource, $attribute), function ($value) use ($resource, $attribute) {
                $this->value = call_user_func($this->resolveCallback, $value, $resource, $attribute);
            });
        }
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     */
    public function callback($callback)
    {
        $this->mediaCallback = $callback;

        return $this;
    }

    /**
     * Set the multiple.
     *
     * @return $this
     */
    public function multiple()
    {
        return $this->withMeta(['multiple' => true]);
    }
}
