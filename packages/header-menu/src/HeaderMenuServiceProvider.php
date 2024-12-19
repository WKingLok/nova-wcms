<?php

namespace Packages\HeaderMenu;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Packages\HeaderMenu\Models\HeaderMenu as HeaderMenuModel;
use Packages\HeaderMenu\Policies\HeaderMenuPolicy;

class HeaderMenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('header-menu', fn() => new HeaderMenu());

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Register policy
         */
        Gate::policy(HeaderMenuModel::class, HeaderMenuPolicy::class);
    }
}
