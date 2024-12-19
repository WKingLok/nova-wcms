<?php

namespace Packages\Approval;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Illuminate\Support\Facades\Route;

class ApprovalNovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Register nova js and css file
         */
        Nova::serving(function (ServingNova $event) {
            Nova::script('approval', __DIR__ . '/../dist/js/nova.js');
            Nova::style('approval', __DIR__ . '/../dist/css/nova.css');
        });

        /**
         * Register nova routes
         */
        Route::middleware(['nova', 'auth:administrators'])
            ->group(__DIR__ . '/../routes/web.php');
    }
}
