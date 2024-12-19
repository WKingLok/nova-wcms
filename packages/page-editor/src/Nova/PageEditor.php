<?php

namespace Packages\PageEditor\Nova;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Slug;
use Packages\PageEditor\NovaFields\EditorButton;
use Packages\Translatable\Translatable;
use Packages\TranslatablePanel\TranslatablePanel;
use Laravel\Nova\Query\Search\SearchableRelation;

class PageEditor extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Packages\PageEditor\Models\Page>
     */
    public static $model = \Packages\PageEditor\Models\Page::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * Get the searchable columns for the resource.
     *
     * @return array
     */
    public static function searchableColumns()
    {
        return [
            'id',
            'slug',
            new SearchableRelation('translations', 'title')
        ];
    }
    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return "Pages";
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return "Page";
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Slug', 'slug')
                ->rules('required'),
            Text::make('Path', 'path')
                ->help(config('app.url') . "/xxx")
                ->rules('required'),
            TranslatablePanel::make("Content", [
                Translatable::make(Text::make('Title')),
                Translatable::make(Text::make('Description'))
                    ->hideFromIndex(),
            ]),
            Boolean::make('Enabled')
                ->sortable(),
            EditorButton::make("Editor")
                ->exceptOnForms(),
            TranslatablePanel::make("SEO", [
                Translatable::make(Text::make('Title', 'seo_title'))
                    ->hideFromIndex(),
                Translatable::make(Text::make('Description', 'seo_description'))
                    ->hideFromIndex(),
                Translatable::make(Text::make('Keywords', 'seo_keywords'))
                    ->hideFromIndex(),
            ]),
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
