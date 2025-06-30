<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doacao;
use App\Models\Pessoa;
use App\Models\Produto;
use Illuminate\Support\Facades\Auth;

class DoacaoController extends Controller
{
    /**
     * Exibe a lista de todas as doações.
     * Corresponde ao botão "Doações" no menu.
     */
    public function index()
    {
        // Busca todas as doações e inclui o nome do doador (Pessoa)
        $doacoes = Doacao::with('doador')->latest()->paginate(15);

        return view('doacoes.index', compact('doacoes'));
    }

    /**
     * Mostra o formulário para registrar uma nova doação.
     * Corresponde ao botão "Registrar Nova Doação".
     */
    public function create()
    {
        // Precisamos de uma lista de doadores (Pessoa) e produtos para o formulário
        $doadores = Pessoa::where('tipo_beneficiario', 'DOADOR')->orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();

        return view('doacoes.create', compact('doadores', 'produtos'));
    }

        /**
     * Salva uma nova doação no banco de dados.
     */
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'pessoa_id' => 'required|exists:pessoa,id',
            'data_doacao' => 'required|date',
            'status_doacao' => 'required|string',
            'itens' => 'required|array|min:1',
            'itens.*.produto_id' => 'required|exists:produto,id',
            'itens.*.quantidade' => 'required|integer|min:1',
        ]);

        // Cria a doação principal
        $doacao = Doacao::create([
            'pessoa_id' => $request->pessoa_id,
            'data_doacao' => $request->data_doacao,
            'instante' => now(),
            'status_doacao' => $request->status_doacao,
            'status_entrega' => 'PENDENTE', // Padrão
        ]);

        // --- LÓGICA CORRIGIDA PARA EVITAR DUPLICATAS ---
        // 1. Agrega os itens: junta produtos duplicados e soma as quantidades.
        $itensAgregados = [];
        foreach ($request->itens as $itemData) {
            $produtoId = $itemData['produto_id'];
            $quantidade = (int)$itemData['quantidade'];

            if (isset($itensAgregados[$produtoId])) {
                // Se o produto já foi adicionado, apenas soma a quantidade
                $itensAgregados[$produtoId] += $quantidade;
            } else {
                // Se for a primeira vez, define a quantidade inicial
                $itensAgregados[$produtoId] = $quantidade;
            }
        }

        // 2. Salva os itens agregados no banco de dados.
        foreach ($itensAgregados as $produtoId => $quantidade) {
            $doacao->itens()->create([
                'produto_id' => $produtoId,
                'quantidade' => $quantidade,
            ]);
            // Futuramente, aqui entrará a lógica para atualizar o estoque geral.
        }

        return redirect()->route('doacoes.index')->with('success', 'Doação registrada com sucesso!');
    }
}