<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class EstoqueController extends Controller
{
    /**
     * Mostra a lista de itens do estoque, com filtro de pesquisa.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Item::query();

        if ($search) {
            $query->where('nome_item', 'like', "%{$search}%");
        }

        $itens = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('estoque.index', compact('itens'));
    }

    /**
     * Mostra o formulário para criar um novo item.
     */
    public function create()
    {
        return view('estoque.create');
    }

    /**
     * Guarda um novo item no estoque.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome_item' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:1',
            'categoria_principal' => 'required|string',
            'descricao' => 'nullable|string',
            'foto_item' => 'nullable|image|max:2048',
            'sub_categoria' => 'nullable|string',
            'tamanho' => 'nullable|string',
            'genero_roupa' => 'nullable|string',
            'data_validade' => 'nullable|date',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_item')) {
            $fotoPath = $request->file('foto_item')->store('fotos_estoque', 'public');
        }

        $detalhes = [
            'sub_categoria' => $validatedData['sub_categoria'] ?? null,
            'tamanho' => $validatedData['tamanho'] ?? null,
            'genero' => $validatedData['genero_roupa'] ?? null,
            'data_validade' => $validatedData['data_validade'] ?? null,
        ];

        Item::create([
            'nome_item' => $validatedData['nome_item'],
            'quantidade' => $validatedData['quantidade'],
            'categoria_principal' => $validatedData['categoria_principal'],
            'descricao' => $validatedData['descricao'],
            'foto_path' => $fotoPath,
            'detalhes' => $detalhes,
            'status' => 'Disponível',
        ]);

        return redirect()->route('web.estoque.index')->with('success', 'Item adicionado ao estoque com sucesso!');
    }

    /**
     * Mostra os detalhes de um item específico.
     */
    public function show(Item $item)
    {
        return view('estoque.show', compact('item'));
    }

    /**
     * Mostra o formulário para editar um item.
     */
    public function edit(Item $item)
    {
        return view('estoque.edit', compact('item'));
    }

    /**
     * Atualiza um item no estoque.
     */
    public function update(Request $request, Item $item)
    {
        $validatedData = $request->validate([
            'nome_item' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:0',
            'status' => 'required|string|in:Disponível,Danificado,Esgotado',
            'categoria_principal' => 'required|string',
            'descricao' => 'nullable|string',
            'foto_item' => 'nullable|image|max:2048',
            'sub_categoria' => 'nullable|string',
            'tamanho' => 'nullable|string',
            'genero_roupa' => 'nullable|string',
            'data_validade' => 'nullable|date',
        ]);

        $fotoPath = $item->foto_path;
        if ($request->hasFile('foto_item')) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto_item')->store('fotos_estoque', 'public');
        }

        $detalhes = [
            'sub_categoria' => $validatedData['sub_categoria'] ?? null,
            'tamanho' => $validatedData['tamanho'] ?? null,
            'genero' => $validatedData['genero_roupa'] ?? null,
            'data_validade' => $validatedData['data_validade'] ?? null,
        ];

        $item->update([
            'nome_item' => $validatedData['nome_item'],
            'quantidade' => $validatedData['quantidade'],
            'status' => $validatedData['status'],
            'categoria_principal' => $validatedData['categoria_principal'],
            'descricao' => $validatedData['descricao'],
            'foto_path' => $fotoPath,
            'detalhes' => $detalhes,
        ]);

        return redirect()->route('web.estoque.index')->with('success', 'Item atualizado com sucesso!');
    }

    /**
     * Remove um item do estoque.
     */
    public function destroy(Item $item)
    {
        if ($item->foto_path) {
            Storage::disk('public')->delete($item->foto_path);
        }
        $item->delete();
        return redirect()->route('web.estoque.index')->with('success', 'Item excluído com sucesso!');
    }

    /**
     * Procura por itens no estoque com base num termo de pesquisa.
     */
    public function search(Request $request)
    {
        $term = $request->query('term');

        if (empty($term)) {
            return response()->json([]);
        }

        $itens = Item::where('nome_item', 'like', "%{$term}%")
            ->where('quantidade', '>', 0)
            ->where('status', 'Disponível')
            ->limit(10)
            ->get(['id', 'nome_item', 'quantidade']);

        return response()->json($itens);
    }
}
