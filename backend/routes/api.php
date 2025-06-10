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

Route::prefix('v1')->group(function () {
    // Rotas públicas (registro e login)
    Route::post('register', [AutenticacaoController::class, 'register']);
    Route::post('login',    [AutenticacaoController::class, 'login']);

    // Rotas protegidas por Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AutenticacaoController::class, 'logout']);

        // CRUD de estados/cidades/endereço (se for expor)
        Route::get('estados', [EstadoController::class, 'index']);
        Route::post('estados', [EstadoController::class, 'store']);
        Route::get('cidades', [CidadeController::class, 'index']); // opcionalmente filtrar por estado

        // Pessoa
        Route::apiResource('pessoas', PessoaController::class);
        // Telefone de pessoa (se quiser CRUD separado)
        Route::apiResource('telefones', TelefoneController::class);

        // Endereço (se quiser CRUD separado)
        Route::apiResource('enderecos', EnderecoController::class);

        // Funcionário e Beneficiário
        Route::apiResource('funcionarios', FuncionarioController::class);
        Route::apiResource('beneficiarios', BeneficiarioController::class);

        // Doação
        Route::apiResource('doacoes', DoacaoController::class);

        // Relatórios
        Route::get('relatorios/doacoes', [RelatorioController::class, 'gerarDoacoes']);

        // Produto / Categoria
        Route::apiResource('categorias', CategoriaController::class);
        Route::apiResource('produtos', ProdutoController::class);
        // (não precisa expor item_doacao / categoria_produto separadamente,
        // pois gerenciaremos isso dentro de DoacaoController e ProdutoController)
    });
});
