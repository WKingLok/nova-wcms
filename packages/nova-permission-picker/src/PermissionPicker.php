<?php

namespace Packages\PermissionPicker;

use Laravel\Nova\Fields\Field;

class PermissionPicker extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'permission-picker';

    /**
     * Set the options that may be selected.
     *
     * @param  array  $options
     * @return $this
     */
    public function options($options)
    {
        return $this->withMeta(['options' => $options]);
    }
}
