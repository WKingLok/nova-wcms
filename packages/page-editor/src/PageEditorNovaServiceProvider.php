<?php

namespace Packages\PageEditor;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Packages\PageEditor\Nova\PageEditor;
use Illuminate\Support\Facades\Route;
use Packages\PageEditor\Nova\ShareWidget;

class PageEditorNovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Register routes
         */
        $this->routes();

        /**
         * Register nova resources
         */
        Nova::resources([
            PageEditor::class,
            ShareWidget::class,
        ]);

        /**
         * Register nova js and css file
         */
        Nova::serving(function (ServingNova $event) {
            Nova::script('page-editor', __DIR__ . '/../dist/js/nova.js');
            Nova::style('page-editor', __DIR__ . '/../dist/css/nova.css');
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        Route::middleware(['nova', 'auth:administrators'])
            ->group(__DIR__ . '/../routes/web.php');
    }
}
