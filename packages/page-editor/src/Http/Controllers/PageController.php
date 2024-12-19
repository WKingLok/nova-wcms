<?php

namespace Packages\PageEditor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Packages\PageEditor\Http\Resources\PageCollection;
use Packages\PageEditor\Http\Resources\WidgetCollection;
use Packages\PageEditor\Models\Page;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function page(Request $request, $path = null)
    {
        if ($path) {
            $path = '/' . $path;
            $page = Page::where('path', $path);
        } else {
            $page = Page::where('slug', 'home');
        }

        $page = $page
            ->with(['widgets', 'widgets.media', 'media', 'translations', 'translations.media'])
            ->where('enabled', true)
            ->first();

        //check detail page
        if (!$page) {
            $pagePath = Page::pluck('path')->filter(function ($page) use ($path) {
                return Str::is($page, $path);
            })->first();

            if ($_path = str_replace(strtok($pagePath, '*'), '', $path)) {
                $id = explode('/', $_path);
                $request->merge(['id' => data_get($id, 0, $_path)]);
            }

            $page = Page::where('path', $pagePath)
                ->with(['widgets', 'widgets.media', 'media', 'translations', 'translations.media'])
                ->where('enabled', true)
                ->first();
        }

        if (!$page) {
            return Inertia::render('Errors/404');
        }

        $widgets = $page->widgets->sortBy('ranking');

        return Inertia::render('Dynamic', [
            'page' => new PageCollection($page),
            'widgets' => WidgetCollection::collection($widgets)
        ]);
    }
}
