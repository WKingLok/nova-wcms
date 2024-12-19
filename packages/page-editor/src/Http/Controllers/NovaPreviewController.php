<?php

namespace Packages\PageEditor\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Laravel\Nova\Http\Requests\NovaRequest;

class NovaPreviewController extends Controller
{
    /**
     * Show Resource Update Attached page using Inertia.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Inertia\Response
     */
    public function __invoke(NovaRequest $request)
    {
        Inertia::setRootView("page-builder::layout");
        return Inertia::render('Preview', [
            'preview' => $request->preview ? json_decode($request->preview, true) : null
        ]);
    }
}
