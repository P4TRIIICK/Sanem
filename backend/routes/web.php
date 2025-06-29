<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BeneficiarioWebController;
use App\Http\Controllers\EstoqueController;
use Spatie\Permission\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware('web')->group(function () {

    // ... Suas rotas públicas e de login ...
    Route::get('/', [HomeController::class, 'index'])->name('home.public');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // --- ROTAS PROTEGIDAS ---
    Route::middleware([
        'auth',
        RoleMiddleware::class . ':Administrador|Consultor',
    ])->group(function () {

        // ... Suas outras rotas protegidas (logout, dashboard) ...
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // --- BENEFICIÁRIOS (ROTAS WEB COMPLETAS) ---
        // As 7 rotas padrão de um resource (index, create, store, show, edit, update, destroy)
        Route::get('/beneficiarios', [BeneficiarioWebController::class, 'index'])->name('web.beneficiarios.index');
        Route::get('/beneficiarios/create', [BeneficiarioWebController::class, 'create'])->name('web.beneficiarios.create');
        Route::post('/beneficiarios', [BeneficiarioWebController::class, 'store'])->name('web.beneficiarios.store');
        Route::get('/beneficiarios/{pessoa}', [BeneficiarioWebController::class, 'show'])->name('web.beneficiarios.show'); // Rota para ver detalhes
        Route::get('/beneficiarios/{pessoa}/edit', [BeneficiarioWebController::class, 'edit'])->name('web.beneficiarios.edit'); // Rota para mostrar o form de edição
        Route::put('/beneficiarios/{pessoa}', [BeneficiarioWebController::class, 'update'])->name('web.beneficiarios.update'); // Rota para processar a edição
        Route::delete('/beneficiarios/{pessoa}', [BeneficiarioWebController::class, 'destroy'])->name('web.beneficiarios.destroy'); // Rota para apagar

        
        // Rota para o formulário de aprovação (rota customizada)
        Route::get('/beneficiarios/{pessoa}/status', [BeneficiarioWebController::class, 'showApprovalForm'])->name('web.beneficiarios.approvalForm');
        Route::post('/beneficiarios/{pessoa}/status', [BeneficiarioWebController::class, 'processApproval'])->name('web.beneficiarios.processApproval');


        // --- ESTOQUE ---
        Route::get('/estoque', [EstoqueController::class, 'index'])->name('estoque.index');
        Route::get('/estoque/create', [EstoqueController::class, 'create'])->name('estoque.create');
        Route::post('/estoque', [EstoqueController::class, 'store'])->name('estoque.store');

    });

});
