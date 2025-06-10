<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Categoria;

class ProdutoController extends Controller
{
    // GET /api/v1/produtos
    public function index()
    {
        return response()->json(Produto::with('categorias')->get(), 200);
    }

    // POST /api/v1/produtos
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'       => 'required|string|max:255',
            'qualidade'  => 'required|boolean',
            'categorias' => 'nullable|array',
            'categorias.*' => 'integer|exists:categoria,id'
        ]);

        $produto = Produto::create([
            'nome'      => $data['nome'],
            'qualidade' => $data['qualidade']
        ]);

        if (! empty($data['categorias'])) {
            $produto->categorias()->sync($data['categorias']);
        }

        return response()->json($produto->load('categorias'), 201);
    }

    // GET /api/v1/produtos/{id}
    public function show($id)
    {
        $produto = Produto::with('categorias')->find($id);
        if (! $produto) {
            return response()->json(['error'=>'Produto não encontrado'],404);
        }
        return response()->json($produto, 200);
    }

    // PUT /api/v1/produtos/{id}
    public function update(Request $request, $id)
    {
        $produto = Produto::find($id);
        if (! $produto) {
            return response()->json(['error'=>'Produto não encontrado'],404);
        }
        $data = $request->validate([
            'nome'       => 'sometimes|required|string|max:255',
            'qualidade'  => 'sometimes|required|boolean',
            'categorias' => 'nullable|array',
            'categorias.*' => 'integer|exists:categoria,id'
        ]);

        $produto->update([
            'nome'      => $data['nome'] ?? $produto->nome,
            'qualidade' => $data['qualidade'] ?? $produto->qualidade,
        ]);

        if (isset($data['categorias'])) {
            $produto->categorias()->sync($data['categorias']);
        }

        return response()->json($produto->load('categorias'), 200);
    }

    // DELETE /api/v1/produtos/{id}
    public function destroy($id)
    {
        $produto = Produto::find($id);
        if (! $produto) {
            return response()->json(['error'=>'Produto não encontrado'],404);
        }
        $produto->delete();
        return response()->json(['message'=>'Excluído'],200);
    }
}
