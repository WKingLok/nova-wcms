<?php

namespace Packages\HeaderMenu\Nova;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;
use Laravel\Nova\Fields\Boolean;
use Packages\Translatable\Translatable;
use Packages\TranslatablePanel\TranslatablePanel;
use Laravel\Nova\Query\Search\SearchableRelation;
use Laravel\Nova\Fields\Select;
use Packages\HeaderMenu\Enums\HeaderMenuType;
use Alexwenzel\DependencyContainer\HasDependencies;
use Alexwenzel\DependencyContainer\DependencyContainer;
use Packages\PageEditor\Models\Page;

class HeaderMenu extends Resource
{
    use HasDependencies;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Packages\HeaderMenu\Models\HeaderMenu>
     */
    public static $model = \Packages\HeaderMenu\Models\HeaderMenu::class;

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
        return "Menus";
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return "Menu";
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
            TranslatablePanel::make("Label", [
                Translatable::make(Text::make('Label')),
            ]),
            Select::make('Type', 'type', fn() => data_get($this->type, 'description'))
                ->options(HeaderMenuType::asSelectArray())
                ->rules('required'),
            Text::make('Path', 'slug',  fn() => $this->path)
                ->exceptOnForms(),
            DependencyContainer::make([
                Select::make('Page', 'page_id')
                    ->options(Page::get()->pluck('title', 'id')),
            ])
                ->dependsOn('type', HeaderMenuType::PAGE)
                ->onlyOnForms(),
            DependencyContainer::make([
                Text::make('Url', 'url'),
            ])
                ->dependsOn('type', HeaderMenuType::EXTERNAL)
                ->onlyOnForms(),
            Boolean::make('Enabled')
                ->sortable(),
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
