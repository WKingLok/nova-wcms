<?php

namespace Packages\PageEditor\NovaFields;

use Laravel\Nova\Fields\Field;

class EditorButton extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'editor-button';

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|\Closure|callable|object|null  $attribute
     * @param  (callable(mixed, mixed, ?string):(mixed))|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct("", $attribute, $resolveCallback);
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        //add resource id
        $this->withMeta(['editorURL' => route('nova.page-editor.editor', ['page-editors',  $resource->id])]);
    }
}
