<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * As URIs que devem ser excluídas da verificação CSRF.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Aqui você pode colocar as rotas de API que não devem exigir CSRF,
        // por exemplo:
        'v1/register',
        'v1/login',
        'v1/logout',
        // Ou se quiser isentar TUDO que começar com v1/:
        // 'v1/*',
    ];
}
