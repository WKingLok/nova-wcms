<?php

namespace Packages\Approval;

use Illuminate\Support\ServiceProvider;

class ApprovalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Load migration files
         */
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        /**
         * Load configuration files
         */
        $this->mergeConfigFrom(
            __DIR__ . '/../config/approval.php',
            'approval'
        );

        /**
         * Register singleton services
         */
        $this->app->singleton('approval', fn() => new Approval());
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
