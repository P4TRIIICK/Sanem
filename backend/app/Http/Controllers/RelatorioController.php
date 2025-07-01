<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doacao;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    /**
     * Gera e exibe o relatório de doações do mês atual.
     */
    public function gerarRelatorioDoacoesMensal()
    {
        // 1. Define o período (mês e ano atuais)
        $mesAtual = Carbon::now()->month;
        $anoAtual = Carbon::now()->year;

        // 2. Busca todas as doações do mês atual
        // O método with() carrega os relacionamentos para otimizar a consulta
        $doacoes = Doacao::with(['beneficiario', 'funcionario', 'itens'])
            ->whereMonth('data_doacao', $mesAtual)
            ->whereYear('data_doacao', $anoAtual)
            ->orderBy('data_doacao', 'asc')
            ->get();

        // 3. Passa os dados para a view do relatório
        return view('relatorios.doacoes_mensal', [
            'doacoes' => $doacoes,
            'mes' => ucfirst(Carbon::now()->locale('pt_BR')->translatedFormat('F')),
            'ano' => $anoAtual,
        ]);
    }
}
