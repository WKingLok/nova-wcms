<?php

namespace Packages\HeaderMenu;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Illuminate\Support\Facades\Route;
use Packages\HeaderMenu\Nova\HeaderMenu;

class HeaderMenuNovaServiceProvider extends NovaApplicationServiceProvider
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
            HeaderMenu::class
        ]);

        /**
         * Register nova js and css file
         */
        Nova::serving(function (ServingNova $event) {
            Nova::script('header-menu', __DIR__ . '/../dist/js/nova.js');
            Nova::style('header-menu', __DIR__ . '/../dist/css/nova.css');
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
