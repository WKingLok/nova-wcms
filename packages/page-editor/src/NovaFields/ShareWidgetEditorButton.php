<?php

namespace Packages\PageEditor\NovaFields;

use Laravel\Nova\Fields\Field;

class ShareWidgetEditorButton extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'share-widget-editor-button';

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
        $this->withMeta(['editorURL' => route('nova.page-editor.share-widgets.editor', ['share-widgets', $resource->id])]);
    }
}
