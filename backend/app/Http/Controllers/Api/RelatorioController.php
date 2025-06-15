<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Relatorio;
use App\Models\Doacao;
use App\Models\Funcionario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    // GET /api/v1/relatorios/doacoes?data_inicio=...&data_fim=...
    public function gerarDoacoes(Request $request)
    {
        $data = $request->validate([
            'data_inicio' => 'required|date',
            'data_fim'    => 'required|date|after_or_equal:data_inicio'
        ]);

        $usuario = Auth::user(); // deve ser funcionário
        $func = $usuario->funcionario;
        if (! $func) {
            return response()->json(['error'=>'Apenas funcionários podem gerar relatório'],403);
        }

        // obter doações no intervalo e agrupar por produto
        $doacoes = Doacao::whereDate('instante','>=',$data['data_inicio'])
            ->whereDate('instante','<=',$data['data_fim'])
            ->where('status_doacao','DOACAO_APTA')
            ->with('itens.produto')
            ->get();

        // calcular totais por produto
        $totais = [];
        foreach ($doacoes as $d) {
            foreach ($d->itens as $item) {
                $pid = $item->produto_id;
                if (! isset($totais[$pid])) {
                    $totais[$pid] = [
                        'produto' => $item->produto->nome,
                        'quantidade' => 0
                    ];
                }
                $totais[$pid]['quantidade'] += $item->quantidade;
            }
        }

        // salvar o relatório no banco
        $rel = Relatorio::create([
            'data_relatorio' => now()->toDateString(),
            'formato'        => 'JSON',
            'tipo_relatorio' => 'DOACOES_RECEBIDAS',
            'descricao'      => json_encode($totais),
            'funcionario_id' => $func->id
        ]);

        return response()->json([
            'relatorio' => $rel,
            'totais'    => $totais
        ], 200);
    }
}
