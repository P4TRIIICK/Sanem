<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BeneficiarioWebController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\FuncionarioController;
use Spatie\Permission\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROTAS PÚBLICAS ---
Route::get('/', [HomeController::class, 'index'])->name('home.public');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// --- ROTAS PROTEGIDAS POR AUTENTICAÇÃO ---
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- ROTAS PARA BENEFICIÁRIOS E ESTOQUE (Admin e Consultor) ---
    Route::middleware(RoleMiddleware::class . ':Administrador|Consultor')->group(function () {
        Route::resource('beneficiarios', BeneficiarioWebController::class)
            ->parameters(['beneficiarios' => 'pessoa'])
            ->names('web.beneficiarios');
        
        Route::get('/beneficiarios/{pessoa}/status', [BeneficiarioWebController::class, 'showApprovalForm'])->name('web.beneficiarios.approvalForm');
        Route::post('/beneficiarios/{pessoa}/status', [BeneficiarioWebController::class, 'processApproval'])->name('web.beneficiarios.processApproval');
        
        Route::resource('estoque', EstoqueController::class)->names('web.estoque');
    });

    // --- ROTAS PARA FUNCIONÁRIOS (Apenas Administrador) ---
    Route::middleware(RoleMiddleware::class . ':Administrador')->group(function () {
        // CORREÇÃO: Usando Route::resource para criar todas as rotas, incluindo a 'show'.
        Route::resource('funcionarios', FuncionarioController::class)
            ->parameters(['funcionarios' => 'funcionario'])
            ->names('web.funcionarios');
    });
});
