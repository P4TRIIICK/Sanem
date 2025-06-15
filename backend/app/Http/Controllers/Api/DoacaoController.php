<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doacao;
use App\Models\ItemDoacao;
use App\Models\Pessoa;
use App\Models\Produto;
use Illuminate\Support\Facades\DB;

class DoacaoController extends Controller
{
    // GET /api/v1/doacoes?status=...&data_inicio=YYYY-MM-DD&data_fim=YYYY-MM-DD
    public function index(Request $request)
    {
        $query = Doacao::with(['pessoa','itens.produto']);

        if ($request->has('status')) {
            $query->where('status_doacao', $request->input('status'));
        }
        if ($request->has('data_inicio')) {
            $query->whereDate('instante', '>=', $request->input('data_inicio'));
        }
        if ($request->has('data_fim')) {
            $query->whereDate('instante', '<=', $request->input('data_fim'));
        }

        return response()->json($query->get(), 200);
    }

    // POST /api/v1/doacoes
    public function store(Request $request)
    {
        $data = $request->validate([
            'pessoa_id' => 'required|integer|exists:pessoa,id',
            'itens'     => 'required|array|min:1',
            'itens.*.produto_id'  => 'required|integer|exists:produto,id',
            'itens.*.quantidade'  => 'required|integer|min:1',
        ]);

        $pessoa = Pessoa::find($data['pessoa_id']);
        // validar se pode doar (ex.: tipo_beneficiario DOADOR ou BENEFICIARIO_DOADOR)
        if (! in_array($pessoa->tipo_beneficiario, ['DOADOR','BENEFICIARIO_DOADOR'])) {
            return response()->json(['error'=>'Pessoa não apta a doar'], 422);
        }

        DB::beginTransaction();
        try {
            // cria doação
            $doacao = Doacao::create([
                'instante'     => now(),
                'status_doacao'=> 'DOACAO_APTA',
                'pessoa_id'    => $data['pessoa_id']
            ]);

            // cria itens da doação
            foreach ($data['itens'] as $item) {
                ItemDoacao::create([
                    'doacao_id'   => $doacao->id,
                    'produto_id'  => $item['produto_id'],
                    'quantidade'  => $item['quantidade']
                ]);
            }

            DB::commit();
            return response()->json($doacao->load('itens.produto'), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error'=>'Erro ao criar doação','details'=>$e->getMessage()], 500);
        }
    }

    // GET /api/v1/doacoes/{id}
    public function show($id)
    {
        $doacao = Doacao::with(['pessoa','itens.produto'])->find($id);
        if (! $doacao) {
            return response()->json(['error'=>'Doação não encontrada'],404);
        }
        return response()->json($doacao, 200);
    }

    // PUT e DELETE podem ser implementados conforme regra de negócio se necessário
}
