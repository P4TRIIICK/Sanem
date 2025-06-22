<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BeneficiarioWebController; // <-- 'use' já estava aqui

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROTAS PÚBLICAS ---
Route::get('/', [HomeController::class, 'index'])->name('home.public');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


// --- ROTA DE APRESENTAÇÃO ---
Route::get('/modo-apresentacao', function () {
    $mockUser = new \stdClass();
    $mockUser->name = 'Equipe Sanem';
    return view('dashboard', ['user' => $mockUser]);
})->name('presentation.dashboard');


// --- ROTAS PRIVADAS (EXIGEM AUTENTICAÇÃO) ---
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // --- PAINEL ADMINISTRATIVO (PROTEGIDO POR PAPEL) ---
    Route::middleware('role:Administrador|Consultor')->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // --- MÓDULO DE BENEFICIÁRIOS ---
        Route::get('/beneficiarios', [BeneficiarioWebController::class, 'index'])->name('beneficiarios.index');
        Route::get('/beneficiarios/create', [BeneficiarioWebController::class, 'create'])->name('beneficiarios.create');
        
        // vvvvvv ROTA ADICIONADA AQUI vvvvvv
        // Rota para RECEBER os dados do formulário de criação
        Route::post('/beneficiarios', [BeneficiarioWebController::class, 'store'])->name('beneficiarios.store');
        // ^^^^^^ ROTA ADICIONADA AQUI ^^^^^^

    });
});