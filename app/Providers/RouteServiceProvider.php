<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The namespace applied to controller routes.
     *
     * This is set as a default namespace for the application controllers.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Bootstrap any application services and define routes.
     *
     * This method is called during the application bootstrapping process.
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // Define routes for API with 'api' middleware
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            // Define routes for web with 'web' middleware
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure rate limiting for the application.
     *
     * This helps in protecting the application from abusive requests.
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            // Limit API requests to 60 per minute per user or IP
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
