<?php

namespace Packages\PageEditor\Nova;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Packages\PageEditor\NovaFields\EditorButton;
use Packages\Translatable\Translatable;
use Packages\TranslatablePanel\TranslatablePanel;
use Laravel\Nova\Query\Search\SearchableRelation;
use Packages\PageEditor\NovaFields\ShareWidgetEditorButton;

class ShareWidget extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Packages\PageEditor\Models\Page>
     */
    public static $model = \Packages\PageEditor\Models\ShareWidget::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Get the searchable columns for the resource.
     *
     * @return array
     */
    public static function searchableColumns()
    {
        return [
            'id',
            'name'
        ];
    }
    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return "Share Widgets";
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return "Share Widget";
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $widgets = collect(config('page-editor.share_components', []))
            ->where('system', '!=', true)
            ->pluck('label', 'key');

        return [
            Text::make('Name', 'name'),
            Select::make('Widget', 'component_key')
                ->options($widgets)
                ->onlyOnForms()
                ->hideWhenUpdating(),
            Boolean::make('Enabled')
                ->sortable(),
            ShareWidgetEditorButton::make("Editor")
                ->exceptOnForms(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
