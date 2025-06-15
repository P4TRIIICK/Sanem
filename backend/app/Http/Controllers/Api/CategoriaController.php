<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    // GET /api/v1/categorias
    public function index()
    {
        return response()->json(Categoria::all(), 200);
    }

    // POST /api/v1/categorias
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255'
        ]);
        $cat = Categoria::create($data);
        return response()->json($cat, 201);
    }

    // GET /api/v1/categorias/{id}
    public function show($id)
    {
        $cat = Categoria::find($id);
        if (! $cat) {
            return response()->json(['error'=>'Categoria não encontrada'],404);
        }
        return response()->json($cat, 200);
    }

    // PUT /api/v1/categorias/{id}
    public function update(Request $request, $id)
    {
        $cat = Categoria::find($id);
        if (! $cat) {
            return response()->json(['error'=>'Categoria não encontrada'],404);
        }
        $data = $request->validate([
            'nome' => 'required|string|max:255'
        ]);
        $cat->update($data);
        return response()->json($cat, 200);
    }

    // DELETE /api/v1/categorias/{id}
    public function destroy($id)
    {
        $cat = Categoria::find($id);
        if (! $cat) {
            return response()->json(['error'=>'Categoria não encontrada'],404);
        }
        $cat->delete();
        return response()->json(['message'=>'Excluída'],200);
    }
}
