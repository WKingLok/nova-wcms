<?php

namespace Packages\Tinymce;

use Laravel\Nova\Fields\Field;

class Tinymce extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'tinymce';


    /**
     * Set TinyMCE templates
     *
     * @param  int  $size
     * @return $this
     */
    public function templates(array $templates = [])
    {
        return $this->withMeta(['templates' => $templates]);
    }
}
