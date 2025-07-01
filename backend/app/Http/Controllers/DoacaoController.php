<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pessoa;
use App\Models\Item;
use App\Models\Doacao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoacaoController extends Controller
{
    /**
     * Mostra o histórico de doações, com filtro por data.
     */
    public function index(Request $request)
    {
        $dataFiltro = $request->query('data_filtro');

        $query = Doacao::with(['beneficiario', 'funcionario', 'itens']);

        if ($dataFiltro) {
            $query->whereDate('data_doacao', $dataFiltro);
        }

        $doacoes = $query->orderBy('data_doacao', 'desc')->paginate(15);

        return view('doacoes.index', compact('doacoes'));
    }

    /**
     * Mostra o formulário para criar uma nova doação.
     */
    public function create()
    {
        return view('doacoes.create');
    }

    /**
     * Guarda uma nova doação no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pessoa_id' => 'required|exists:pessoa,id',
            'itens' => 'required|array|min:1',
            'itens.*.item_id' => 'required|exists:items,id',
            'itens.*.quantidade' => 'required|integer|min:1',
        ]);

        $pessoa = Pessoa::findOrFail($request->pessoa_id);
        $itensDoados = $request->itens;
        $totalItensEsteMes = $this->getTotalItensDoadosNoMes($pessoa->id);

        // Validações das regras de negócio
        if (($totalItensEsteMes + collect($itensDoados)->sum('quantidade')) > 20) {
            return back()->with('error', 'Limite mensal de 20 itens excedido para este beneficiário.');
        }

        foreach ($itensDoados as $itemData) {
            if ($itemData['quantidade'] > 3) {
                return back()->with('error', 'Não é permitido doar mais de 3 unidades do mesmo item.');
            }
        }

        DB::transaction(function () use ($pessoa, $itensDoados) {
            $doacao = Doacao::create([
                'pessoa_id' => $pessoa->id,
                'funcionario_id' => Auth::id(),
                'data_doacao' => Carbon::now(),
            ]);

            foreach ($itensDoados as $itemData) {
                $item = Item::find($itemData['item_id']);
                if ($item->quantidade < $itemData['quantidade']) {
                    throw new \Exception("Estoque insuficiente para o item {$item->nome_item}.");
                }

                $doacao->itens()->attach($item->id, ['quantidade_doada' => $itemData['quantidade']]);
                $item->decrement('quantidade', $itemData['quantidade']);
            }
        });

        return redirect()->route('dashboard')->with('success', 'Doação registrada com sucesso!');
    }

    public function show(Doacao $doacao)
    {
        // O Laravel encontra a Doacao automaticamente pelo ID na URL.
        // O método with() carrega os relacionamentos para otimizar a consulta.
        $doacao->load(['beneficiario', 'funcionario', 'itens']);

        return view('doacoes.show', compact('doacao'));
    }

    private function getTotalItensDoadosNoMes($pessoaId)
    {
        return Doacao::where('pessoa_id', $pessoaId)
            ->whereMonth('data_doacao', Carbon::now()->month)
            ->whereYear('data_doacao', Carbon::now()->year)
            ->withCount(['itens as total_itens' => function ($query) {
                $query->select(DB::raw('sum(quantidade_doada)'));
            }])->get()->sum('total_itens');
    }
}
