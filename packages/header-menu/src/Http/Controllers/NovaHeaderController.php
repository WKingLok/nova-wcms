<?php

namespace Packages\HeaderMenu\Http\Controllers;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Laravel\Nova\Menu\Breadcrumb;
use Laravel\Nova\Menu\Breadcrumbs;
use Laravel\Nova\Nova;
use Illuminate\Http\Request;

class NovaHeaderController extends Controller
{
    /**
     * Show Resource Index page using Inertia.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function __invoke(Request $request)
    {
        $resourceClass = "Packages\HeaderMenu\Nova\HeaderMenu";

        return Inertia::render('HeaderMenu.Index', [
            'breadcrumbs' => $this->breadcrumbs($request),
            'resourceName' => $resourceClass::uriKey(),
        ]);
    }

    /**
     * Get breadcrumb menu for the page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Nova\Menu\Breadcrumbs
     */
    protected function breadcrumbs(Request $request)
    {
        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Resources')),
            Breadcrumb::resource("Packages\HeaderMenu\Nova\HeaderMenu"),
        ]);
    }
}
