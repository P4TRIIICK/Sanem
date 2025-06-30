<?php

use Illuminate\Support\Facades\Route;
<<<<<<< Updated upstream
=======
use App\Http\Controllers\DoacaoController;
>>>>>>> Stashed changes
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BeneficiarioWebController;
<<<<<<< Updated upstream
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\FuncionarioController;
=======
>>>>>>> Stashed changes
use Spatie\Permission\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
<<<<<<< Updated upstream
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
        Route::resource('beneficiarios', BeneficiarioWebController::class)->names('web.beneficiarios');
        Route::get('/beneficiarios/{pessoa}/status', [BeneficiarioWebController::class, 'showApprovalForm'])->name('web.beneficiarios.approvalForm');
        Route::post('/beneficiarios/{pessoa}/status', [BeneficiarioWebController::class, 'processApproval'])->name('web.beneficiarios.processApproval');
        
        Route::resource('estoque', EstoqueController::class)->names('web.estoque');
    });

    // --- ROTAS PARA FUNCIONÁRIOS (Apenas Administrador) ---
    Route::middleware(RoleMiddleware::class . ':Administrador')->group(function () {
        Route::resource('funcionarios', FuncionarioController::class)->names('web.funcionarios')->except(['show']);
    });
=======
|
| Estas rotas já rodam dentro do grupo "web" (sessions, CSRF, etc).
|
*/

Route::middleware('web')->group(function () {

    // --- ROTAS PÚBLICAS ---
    Route::get('/', [HomeController::class, 'index'])->name('home.public');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // --- MODO APRESENTAÇÃO SEM AUTENTICAÇÃO ---
    Route::get('/modo-apresentacao', function () {
        $mockUser = new \stdClass();
        $mockUser->name = 'Equipe Sanem';
        return view('dashboard', ['user' => $mockUser]);
    })->name('presentation.dashboard');

    // --- ROTAS PROTEGIDAS (AUTENTICAÇÃO + PAPEL) ---
    Route::middleware([
        'auth',
        RoleMiddleware::class . ':Administrador|Consultor',
    ])->group(function () {

        // Logout
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Beneficiários
        Route::get('/beneficiarios',        [BeneficiarioWebController::class, 'index'])->name('beneficiarios.index');
        Route::get('/beneficiarios/create', [BeneficiarioWebController::class, 'create'])->name('beneficiarios.create');
        Route::post('/beneficiarios',       [BeneficiarioWebController::class, 'store'])->name('beneficiarios.store');

        // Doações
        Route::get('/doacoes', [DoacaoController::class, 'index'])->name('doacoes.index');
        Route::get('/doacoes/registrar', [DoacaoController::class, 'create'])->name('doacoes.create');
        Route::post('/doacoes', [DoacaoController::class, 'store'])->name('doacoes.store');

    });

>>>>>>> Stashed changes
});
