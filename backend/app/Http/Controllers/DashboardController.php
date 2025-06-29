<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiario;
use App\Models\Pessoa;

class DashboardController extends Controller
{
    /**
     * Mostra o painel principal da aplicação.
     */
    public function index()
    {
        // 1. Conta todos os registos na tabela 'beneficiarios'
        // onde o status é 'APROVADO'.
        $totalBeneficiarios = Beneficiario::where('status', 'APROVADO')->count();

        // 2. Conta todas as 'Pessoas' que têm o papel de 'Administrador' OU 'Consultor'.
        $totalFuncionarios = Pessoa::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Administrador', 'Consultor']);
        })->count();

        // 3. Envia as variáveis com os totais para a view do dashboard.
        return view('dashboard', [
            'totalBeneficiarios' => $totalBeneficiarios,
            'totalFuncionarios' => $totalFuncionarios,
        ]);
    }
}
