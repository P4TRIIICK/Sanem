<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit; 
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define para onde o usuário é redirecionado após login (se usar Auth via web).
     */
    public const HOME = '/home';

    /**
     * O namespace que será aplicado às controllers. 
     * Em versões recentes do Laravel, costuma ser null; deixe conforme abaixo:
     *
     * @var string|null
     */
    protected $namespace = null;

    /**
     * Bootstrap de rotas.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // 1) Rotas de API: todas em routes/api.php, usando 'prefix=api' e middleware 'api'
            Route::prefix('api')
                 ->middleware('api')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/api.php'));

            // 2) Rotas de Web: todas em routes/web.php, usando middleware 'web'
            Route::middleware('web')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configuração de rate limiting para API.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
