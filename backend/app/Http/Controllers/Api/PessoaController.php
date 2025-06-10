<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pessoa;

class PessoaController extends Controller
{
    // GET /api/v1/pessoas
    public function index()
    {
        return response()->json(Pessoa::all(), 200);
    }

    // POST /api/v1/pessoas
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'          => 'required|string|max:255',
            'cpf'           => 'required|string|max:20|unique:pessoa,cpf',
            'rg'            => 'nullable|string|max:20',
            'genero'        => 'required|in:MASCULINO,FEMININO,OUTRO',
            'tipo_beneficiario' => 'required|in:BENEFICIARIO,DOADOR,BENEFICIARIO_DOADOR',
            'nascimento'    => 'nullable|date',
            'email'         => 'nullable|email|max:255|unique:pessoa,email',
            'endereco_id'   => 'required|integer|exists:endereco,id'
        ]);

        $pessoa = Pessoa::create($data);
        return response()->json($pessoa, 201);
    }

    // GET /api/v1/pessoas/{id}
    public function show($id)
    {
        $pessoa = Pessoa::with(['endereco','telefones','beneficiario','funcionario'])->find($id);
        if (! $pessoa) {
            return response()->json(['error'=>'Pessoa não encontrada'],404);
        }
        return response()->json($pessoa, 200);
    }

    // PUT /api/v1/pessoas/{id}
    public function update(Request $request, $id)
    {
        $pessoa = Pessoa::find($id);
        if (! $pessoa) {
            return response()->json(['error'=>'Pessoa não encontrada'],404);
        }
        $data = $request->validate([
            'nome'          => 'sometimes|required|string|max:255',
            'cpf'           => "sometimes|required|string|max:20|unique:pessoa,cpf,{$id}",
            'rg'            => 'nullable|string|max:20',
            'genero'        => 'sometimes|required|in:MASCULINO,FEMININO,OUTRO',
            'tipo_beneficiario' => 'sometimes|required|in:BENEFICIARIO,DOADOR,BENEFICIARIO_DOADOR',
            'nascimento'    => 'nullable|date',
            'email'         => "nullable|email|max:255|unique:pessoa,email,{$id}",
            'endereco_id'   => 'sometimes|required|integer|exists:endereco,id'
        ]);
        $pessoa->update($data);
        return response()->json($pessoa, 200);
    }

    // DELETE /api/v1/pessoas/{id}
    public function destroy($id)
    {
        $pessoa = Pessoa::find($id);
        if (! $pessoa) {
            return response()->json(['error'=>'Pessoa não encontrada'],404);
        }
        $pessoa->delete();
        return response()->json(['message'=>'Excluída'],200);
    }
}
