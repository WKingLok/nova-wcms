<?php

namespace Packages\HeaderMenu\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Inertia\Inertia;
use Laravel\Nova\Menu\Breadcrumb;
use Laravel\Nova\Menu\Breadcrumbs;
use Laravel\Nova\Nova;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Packages\HeaderMenu\Facades\HeaderMenu;
use Packages\HeaderMenu\Http\Resources\NovaMenuCollection;

class NovaHeaderMenuReorderController extends Controller
{
    /**
     * @var MenuRepository
     */
    protected $repository;

    /**
     * NovaHeaderMenuReorderController constructor.
     *
     */
    public function __construct() {}

    /**
     * Show Resource Update Attached page using Inertia.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function __invoke(Request $request)
    {
        $resourceClass = "Packages\HeaderMenu\Nova\HeaderMenu";

        $menu = HeaderMenu::getFirstLevel();

        Gate::authorize('viewAny', HeaderMenu::class);

        return Inertia::render('HeaderMenu.Reorder', [
            'viaResource' => $resourceClass::uriKey(),
            'menus' => NovaMenuCollection::collection($menu),
            'breadcrumbs' => $this->breadcrumbs(),
        ]);
    }

    public function update(Request $request)
    {
        try {
            Gate::authorize('reorder', HeaderMenu::class);
            HeaderMenu::reorder($request->menus);
        } catch (AuthorizationException $th) {
            return response()->json([
                'message' => 'This action is unauthorized.',
            ], 403);
        }
    }

    /**
     * Get breadcrumb menu for the page.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Laravel\Nova\Menu\Breadcrumbs
     */
    protected function breadcrumbs()
    {
        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Resources')),
            Breadcrumb::make(__('Menus'), "/resources/header-menus"),
            Breadcrumb::make(__('Reorder'))
        ]);
    }
}
