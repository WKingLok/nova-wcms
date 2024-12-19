<?php

namespace Packages\PageEditor;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Gate;
use Packages\PageEditor\Models\Page;
use Packages\PageEditor\Models\ShareWidget;
use Packages\PageEditor\Policies\PagePolicy;
use Packages\PageEditor\Policies\ShareWidgetPolicy;

class PageEditorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('page-editor', fn() => new PageEditor());

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
        Gate::policy(Page::class, PagePolicy::class);
        Gate::policy(ShareWidget::class, ShareWidgetPolicy::class);

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'page-builder');

        $this->app->booted(function () {
            $middleware = $this->app->make(Middleware::class);
            $middleware->validateCsrfTokens(except: [
                '/nova-api/page-editors/widget-callback',
            ]);
        });
    }
}
