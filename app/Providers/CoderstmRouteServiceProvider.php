<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class CoderstmRouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // register tunnel domain
            if (config('coderstm.tunnel_domain')) {
                Route::domain(config('coderstm.tunnel_domain'))
                    ->middleware('api')
                    ->namespace($this->namespace)
                    ->group(base_path('routes/api.php'));
            }

            // modify default api route
            if (config('coderstm.domain')) {
                Route::domain(config('coderstm.api_prefix') . '.' . config('coderstm.domain'))
                    ->middleware('api')
                    ->namespace($this->namespace)
                    ->group(base_path('routes/api.php'));
            } else {
                Route::middleware('api')
                    ->prefix(config('coderstm.api_prefix'))
                    ->namespace($this->namespace)
                    ->group(base_path('routes/api.php'));
            }

            if (file_exists(base_path('routes/coderstm/api.php'))) {
                // register tunnel domain
                if (config('coderstm.tunnel_domain')) {
                    Route::domain(config('coderstm.tunnel_domain'))
                        ->middleware('api')
                        ->group(base_path('routes/coderstm/api.php'));
                }

                // modify default api route
                if (config('coderstm.domain')) {
                    Route::domain(config('coderstm.api_prefix') . '.' . config('coderstm.domain'))
                        ->middleware('api')
                        ->group(base_path('routes/coderstm/api.php'));
                } else {
                    Route::middleware('api')
                        ->prefix(config('coderstm.api_prefix'))
                        ->group(base_path('routes/coderstm/api.php'));
                }
            }

            if (file_exists(base_path('routes/coderstm/web.php'))) {
                Route::domain('admin.' . config('coderstm.domain'))
                    ->middleware('web')
                    ->namespace($this->namespace)
                    ->group(base_path('routes/coderstm/web.php'));

                Route::domain('members.' . config('coderstm.domain'))
                    ->middleware('web')
                    ->namespace($this->namespace)
                    ->group(base_path('routes/coderstm/web.php'));
            }

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });
    }
}
