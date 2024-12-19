<?php

namespace Packages\Translatable;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Arr;

class Translatable extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'translatable';

    public function __construct($field)
    {
        $this->withMeta(['field' => $field]);
        parent::__construct($field->name, $field->attribute, $field->resolveCallback);
    }

    /**
     * Override functrion
     * Hydrate the given attribute on the model based on the incoming request.
     */
    public function fillInto(NovaRequest $request, $model, $attribute, $requestAttribute = null)
    {
        $values = Arr::where($request->all(), function ($value, $key) use ($attribute) {
            $check = explode(":", $key);
            return count($check) == 2 && $check[0] == $attribute;
        });

        if (!$model->id) {
            $model->fill($request->all());
            $model->save();
        }

        $model->update($values);
        return null;
    }


    /**
     * Override functrion
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
            if ($resource->translations) {
                $this->value = $resource
                    ->translations
                    ->mapWithKeys(fn($item) => [$item['locale'] => $item[$attribute]]);
            }
        } elseif (is_callable($this->resolveCallback)) {
            tap($this->resolveAttribute($resource, $attribute), function ($value) use ($resource, $attribute) {
                $this->value = call_user_func($this->resolveCallback, $value, $resource, $attribute);
            });
        }
    }
}
