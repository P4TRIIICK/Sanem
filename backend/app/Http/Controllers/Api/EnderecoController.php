<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Endereco;

class EnderecoController extends Controller
{
    // GET /api/v1/enderecos
    public function index()
    {
        return response()->json(Endereco::all(), 200);
    }

    // POST /api/v1/enderecos
    public function store(Request $request)
    {
        $data = $request->validate([
            'logradouro' => 'required|string|max:255',
            'numero'     => 'required|string|max:50',
            'complemento'=> 'nullable|string|max:255',
            'bairro'     => 'nullable|string|max:255',
            'cep'        => 'nullable|string|max:20',
            'cidade_id'  => 'required|integer|exists:cidade,id'
        ]);

        $endereco = Endereco::create($data);
        return response()->json($endereco, 201);
    }

    // GET /api/v1/enderecos/{id}
    public function show($id)
    {
        $endereco = Endereco::find($id);
        if (! $endereco) {
            return response()->json(['error'=>'Endereço não encontrado'],404);
        }
        return response()->json($endereco, 200);
    }

    // PUT /api/v1/enderecos/{id}
    public function update(Request $request, $id)
    {
        $endereco = Endereco::find($id);
        if (! $endereco) {
            return response()->json(['error'=>'Endereço não encontrado'],404);
        }
        $data = $request->validate([
            'logradouro' => 'sometimes|required|string|max:255',
            'numero'     => 'sometimes|required|string|max:50',
            'complemento'=> 'nullable|string|max:255',
            'bairro'     => 'nullable|string|max:255',
            'cep'        => 'nullable|string|max:20',
            'cidade_id'  => 'sometimes|required|integer|exists:cidade,id'
        ]);
        $endereco->update($data);
        return response()->json($endereco, 200);
    }

    // DELETE /api/v1/enderecos/{id}
    public function destroy($id)
    {
        $endereco = Endereco::find($id);
        if (! $endereco) {
            return response()->json(['error'=>'Endereço não encontrado'],404);
        }
        $endereco->delete();
        return response()->json(['message'=>'Excluído'],200);
    }
}
