<?php

namespace Packages\Basic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Packages\Basic\Commands\PermissionGenerate;
use Packages\Basic\Models\Administrator;
use Packages\Basic\Policies\AdministratorPolicy;
use Packages\Basic\Policies\AuditPolicy;

class BasicServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/audit.php',
            'audit'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravellocalization.php',
            'laravellocalization'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/media-library.php',
            'media-library'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/permission.php',
            'permission'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/permission.php',
            'permission'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/translatable.php',
            'translatable'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/wcms.php',
            'wcms'
        );
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
        Gate::policy(Audit::class, AuditPolicy::class);
        Gate::policy(Administrator::class, AdministratorPolicy::class);

        /**
         * Load api routes
         */
        Route::middleware(['api'])
            ->prefix('api')
            ->group(__DIR__ . '/../routes/api.php');

        /**
         * register commands
         */
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('media-library:delete-old-temporary-uploads')
                ->daily()
                ->withoutOverlapping()
                ->onOneServer();
        });

        $middleware = $this->app->make(Middleware::class);
        $middleware->redirectGuestsTo(fn(Request $request) => ($request->is('wcms/*') || $request->is('wcms')) ? route('nova.login') : route('login'));

        if ($this->app->runningInConsole()) {
            $this->commands([
                PermissionGenerate::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/page-editor.php' => config_path('page-editor.php'),
        ]);
    }
}
