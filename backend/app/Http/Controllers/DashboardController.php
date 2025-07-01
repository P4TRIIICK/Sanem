<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiario;
use App\Models\Pessoa;
use App\Models\Item;
use App\Models\Doacao; // Importa o modelo Doacao
use Carbon\Carbon; // Importa a classe Carbon para manipulação de datas

class DashboardController extends Controller
{
    /**
     * Mostra o painel principal da aplicação com dados dinâmicos.
     */
    public function index()
    {
        // 1. Conta beneficiários ativos
        $totalBeneficiarios = Beneficiario::where('status', 'APROVADO')->count();

        // 2. Conta funcionários (excluindo o admin master)
        $totalFuncionarios = Pessoa::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Administrador', 'Consultor']);
        })->where('id', '!=', 1)->count();

        // 3. SOMA a quantidade de todos os itens no estoque
        $totalItensEstoque = Item::sum('quantidade');

        // 4. CONTA o número de doações registadas no mês e ano atuais
        $totalDoacoesMes = Doacao::whereMonth('data_doacao', Carbon::now()->month)
                                 ->whereYear('data_doacao', Carbon::now()->year)
                                 ->count();

        // 5. Envia todas as variáveis para a view
        return view('dashboard', [
            'totalBeneficiarios' => $totalBeneficiarios,
            'totalFuncionarios' => $totalFuncionarios,
            'totalItensEstoque' => $totalItensEstoque,
            'totalDoacoesMes' => $totalDoacoesMes,
        ]);
    }
}
