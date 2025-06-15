<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Não precisamos declarar middlewares globais do “web” ou de sessão.
     * Se por acaso algo for usado, você adiciona manualmente.
     */
    protected $middleware = [
        // Caso queira, mantenha middleware globais de segurança (opcional):
        \Illuminate\Http\Middleware\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
    ];

    /**
     * Grupos de middleware.
     */
    protected $middlewareGroups = [
        // Eliminamos completamente o grupo “web” se não vamos usar
        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // Se for usar Sanctum apenas com Bearer token, não adicione CSRF nem 'EnsureFrontendRequestsAreStateful'
        ],
    ];

    /**
     * Se não for usar autenticação via sessão web, pode até esvaziar esta lista
     * ou deixar apenas middlewares específicos para API.
     */
    protected $routeMiddleware = [
        // Exemplo: se você quiser checar autenticação via token:
        'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        // Outros, se precisar (por exemplo, throttle, bindings, etc.).
        'throttle'     => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'bindings'     => \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ];
}
