<?php

namespace Packages\Basic\Nova;

use App\Nova\Resource;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Query\Search\SearchableMorphToRelation;

class AuditLog extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\OwenIt\Auditing\Models\Audit>
     */
    public static $model = \OwenIt\Auditing\Models\Audit::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'auditable_id';

    /**
     * Get the searchable columns for the resource.
     *
     * @return array
     */
    public static function searchableColumns()
    {
        return [
            new SearchableMorphToRelation('user', 'name', [Administrator::class]),
            new SearchableMorphToRelation('auditable', 'name', [Administrator::class]),
        ];
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
            ID::make('Id'),
            MorphTo::make('User')->types([
                Administrator::class,
            ])->nullable(),
            MorphTo::make('Model', 'auditable')
                ->types([])->nullable(),
            Badge::make('Event', 'event')
                ->map([
                    'created' => 'success',
                    'updated' => 'info',
                    'deleted' => 'danger',
                    'restored' => 'warning',
                ])
                ->sortable(),
            Text::make('IP Address', 'ip_address')
                ->onlyOnDetail(),
            Text::make('User Agent', 'user_agent')
                ->onlyOnDetail(),
            Code::make('Old Values', 'old_values')
                ->json()
                ->onlyOnDetail(),
            Code::make('New Values', 'new_values')
                ->json()
                ->onlyOnDetail(),
            DateTime::make('Modified at', 'updated_at')
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
