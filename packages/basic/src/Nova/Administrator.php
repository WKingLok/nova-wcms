<?php

namespace Packages\Basic\Nova;

use App\Nova\Resource;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\PasswordConfirmation;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Packages\MediaImage\MediaImage;
use Packages\PermissionPicker\PermissionPicker;
use Spatie\Permission\Models\Role;

class Administrator extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Packages\Basic\Models\Administrator>
     */
    public static $model = \Packages\Basic\Models\Administrator::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
        'email'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            MediaImage::make('Avatar', 'avatar')
                ->maxSize(10240)
                ->callback(fn($medias, $model, $collectionName) => $model->syncFromMediaLibraryRequest($medias)->toMediaCollection($collectionName)),
            Text::make('Name'),
            Email::make('Email'),
            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6'),
            PasswordConfirmation::make('Password Confirmation'),
            Select::make(
                'Role',
                'role',
                fn() => $this->getRoleNames()->first()
            )
                ->options(Role::get()->mapWithKeys(fn(Role $item) => [$item['name'] => $item['name']]))
                ->fillUsing(function (NovaRequest $request, $model, $attribute, $requestAttribute) {
                    if ($request->$attribute) {
                        $model->syncRoles([$request->$attribute]);
                    }
                    return null;
                }),
            PermissionPicker::make('Permission', 'permission', fn() => $this->getPermissionNames())
                ->hideFromIndex()
                ->options(collect(config('wcms.permission.administrators', []))->mapWithKeys(fn(array $permission) => [$permission['key'] => $permission['label']]))
                ->fillUsing(function (NovaRequest $request, $model, $attribute, $requestAttribute) {
                    $model->syncPermissions($request->$attribute);
                    return null;
                }),
            Boolean::make('Enabled')
                ->sortable(),
            DateTime::make('Created At')
                ->onlyOnDetail(),
            DateTime::make('Last Modified', 'updated_at')
                ->onlyOnDetail(),

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
