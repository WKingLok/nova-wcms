<?php

namespace Packages\Approval\Nova;

use App\Nova\Resource;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Code;
use Packages\Approval\Approval;
use Packages\Approval\Enums\ApprovalAction;
use Packages\Approval\Enums\ApprovalStatus;
use Illuminate\Database\Eloquent\Model;

abstract class ApprovalResource extends Resource
{
    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $newQuery = parent::indexQuery($request, $query);

        if ($request->archive) {
            return $newQuery->whereHas('approval', function ($query) use ($request) {
                $query->where('status',  ApprovalStatus::ARCHIVE)->where('group_id', $request->approvalGroupId);
            });
        }

        return $newQuery->whereHas('approval', function ($query) {
            $query->where('status', '!=', ApprovalStatus::ARCHIVE);
        });
    }

    /**
     * Resolve the index fields.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Laravel\Nova\Fields\FieldCollection<int, \Laravel\Nova\Fields\Field>
     */
    public function indexFields(NovaRequest $request)
    {
        $availableFields = $this->availableFields($request);

        //push approval status
        $availableFields->push(
            Badge::make('Status', fn($model) => app('approval')->init($model)->displayStatusLabel())
                ->map(Approval::$statusNovaBadgeMapping)
                ->sortable()
        );

        //push approval version
        $availableFields->push(
            Number::make('Version', fn($model) => data_get($model, 'approval.version', 1))
                ->textAlign('center')
                ->sortable()
        );

        $fields = $availableFields
            ->when($request->viaManyToMany(), $this->relatedFieldResolverCallback($request))
            ->filterForIndex($request, $this->resource)
            ->withoutListableFields()
            ->authorized($request)
            ->resolveForDisplay($this->resource);

        return $fields;
    }

    /**
     * Resolve the detail fields.
     *
     * @return \Laravel\Nova\Fields\FieldCollection<int, \Laravel\Nova\Fields\Field>
     */
    public function detailFields(NovaRequest $request)
    {
        $availableFields = $this->availableFields($request);

        $apporvalPanel = Panel::make("Approval");

        $apporvalFields = [
            Number::make('Version', fn($model) => data_get($model, 'approval.version', 1))
                ->textAlign('center')
                ->sortable(),
            Badge::make('Status', fn($model) => app('approval')->init($model, auth()->user())->displayStatusLabel())
                ->map(Approval::$statusNovaBadgeMapping)
                ->sortable(),
            Badge::make('Action', fn($model) => app('approval')->init($model, auth()->user())->displayActionLabel())
                ->map(Approval::$actionNovaBadgeMapping)
                ->sortable(),
            Code::make("History", fn($model) => json_encode(data_get($model, 'approval.history', [])))
                ->json(),
        ];

        Arr::map($apporvalFields, function ($field) use ($apporvalPanel) {
            $field->panel = $apporvalPanel->name;
            $field->assignedPanel = $apporvalPanel;
            return $field;
        });

        $availableFields->push(...$apporvalFields);

        $fields = $availableFields
            ->when($request->viaManyToMany(), $this->fieldResolverCallback($request))
            ->when($this->shouldAddActionsField($request), function ($fields) {
                return $fields->push($this->actionfield());
            })
            ->filterForDetail($request, $this->resource)
            ->authorized($request)
            ->resolveForDisplay($this->resource);


        return $fields;
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Support\Collection<int, \Laravel\Nova\Fields\Field>  $fields
     * @return array<string, mixed>
     */
    public function serializeForIndex(NovaRequest $request, $fields = null)
    {
        return [
            ...parent::serializeForIndex($request, $fields),
            'approval' => true,
        ];
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return array<string, mixed>
     */
    public function serializeForDetail(NovaRequest $request, $resource)
    {
        $approval = app('approval')->init($resource->resource, auth()->user());
        return [
            ...parent::serializeForDetail($request, $resource),
            'approval' => [
                'actions' => $approval->getActions(),
                'status' => $approval->getStatus(),
                'history' => $approval->getHistory(),
            ]
        ];
    }

    /**
     * Register a callback to be called after the resource is created.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public static function afterCreate(NovaRequest $request, Model $model)
    {
        $administrator = auth()->user();
        app('approval')->init($model, $administrator)->handleAction(ApprovalAction::UPDATE);
    }

    /**
     * Register a callback to be called after the resource is created.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public static function afterUpdate(NovaRequest $request, Model $model)
    {
        $administrator = auth()->user();
        app('approval')->init($model, $administrator)->handleAction(ApprovalAction::UPDATE);
    }
}
