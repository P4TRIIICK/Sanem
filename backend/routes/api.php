<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AutenticacaoController;
use App\Http\Controllers\Api\EstadoController;
use App\Http\Controllers\Api\CidadeController;
use App\Http\Controllers\Api\EnderecoController;
use App\Http\Controllers\Api\PessoaController;
use App\Http\Controllers\Api\TelefoneController;
use App\Http\Controllers\Api\FuncionarioController;
use App\Http\Controllers\Api\BeneficiarioController;
use App\Http\Controllers\Api\RelatorioController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\DoacaoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui é onde o permissionamento granular realmente brilha, protegendo
| cada ação que pode ser realizada no sistema.
|
*/

Route::prefix('v1')->group(function () {
    // --- Rotas Públicas ---
    // Não precisam de autenticação ou permissão.
    Route::post('register', [AutenticacaoController::class, 'register']);
    Route::post('login',    [AutenticacaoController::class, 'login']);

    // --- Rotas Protegidas ---
    // Exigem um token válido (autenticação via Sanctum).
    Route::middleware('auth:sanctum')->group(function () {
        
        // Logout só precisa de autenticação.
        Route::post('logout', [AutenticacaoController::class, 'logout']);

        // --- Gestão de Pessoas e Beneficiários ---
        // Apenas usuários com a permissão 'gerenciar-beneficiarios' (Admin, Consultor) podem listar, ver, criar, etc.
        Route::apiResource('pessoas', PessoaController::class)->middleware('permission:gerenciar-beneficiarios');
        Route::apiResource('funcionarios', FuncionarioController::class)->middleware('permission:gerenciar-permissoes'); // Apenas Admin pode gerenciar funcionários
        Route::apiResource('beneficiarios', BeneficiarioController::class)->middleware('permission:gerenciar-beneficiarios');
        
        // Rotas de apoio (endereço, telefone) herdam a permissão de quem as gerencia.
        Route::apiResource('enderecos', EnderecoController::class)->middleware('permission:gerenciar-beneficiarios');
        Route::apiResource('telefones', TelefoneController::class)->middleware('permission:gerenciar-beneficiarios');

        // --- Gestão de Doações ---
        // Permissões separadas para o registro de doação vs. o gerenciamento geral.
        // O método 'store' pode ser acessado tanto por quem gerencia quanto por um Doador.
        Route::post('doacoes', [DoacaoController::class, 'store'])
               ->middleware('permission:gerenciar-doacoes|registrar-propria-doacao');
        
        // As outras ações (listar, ver, atualizar, deletar) são apenas para quem gerencia.
        Route::apiResource('doacoes', DoacaoController::class)
               ->except(['store'])
               ->middleware('permission:gerenciar-doacoes');

        // --- Gestão de Estoque ---
        // Apenas quem tem a permissão 'gerenciar-estoque' pode mexer em produtos e categorias.
        Route::apiResource('categorias', CategoriaController::class)->middleware('permission:gerenciar-estoque');
        Route::apiResource('produtos', ProdutoController::class)->middleware('permission:gerenciar-estoque');

        // --- Relatórios ---
        // Apenas usuários com a permissão 'ver-relatorios' podem acessar.
        Route::get('relatorios/doacoes', [RelatorioController::class, 'gerarDoacoes'])->middleware('permission:ver-relatorios');
        
        // --- Dados Geográficos (geralmente menos restritos, mas protegidos) ---
        // Assumindo que apenas usuários internos precisam consultar.
        Route::get('estados', [EstadoController::class, 'index'])->middleware('role:Administrador|Consultor');
        Route::post('estados', [EstadoController::class, 'store'])->middleware('role:Administrador'); // Apenas admin pode criar estados
        Route::get('cidades', [CidadeController::class, 'index'])->middleware('role:Administrador|Consultor');

    });
});