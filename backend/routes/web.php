<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

// ROTA PÚBLICA
// Rota principal (Home Pública) que todos podem ver.
Route::get('/', [HomeController::class, 'index'])->name('home.public');

// ROTAS DE AUTENTICAÇÃO
// Mostra o formulário de login (acessível por visitantes)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


// ROTA PRIVADA (DASHBOARD)
// Grupo de rotas que só usuários autenticados podem acessar.
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/modo-apresentacao', function () {
    
    $mockUser = new \stdClass();
    $mockUser->name = 'Equipe Sanem';
    $mockUser->email = 'apresentacao@sanem.com';

    return view('dashboard', ['user' => $mockUser]);

})->name('presentation.dashboard');