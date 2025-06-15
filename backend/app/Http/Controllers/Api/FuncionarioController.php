<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\Pessoa;

class FuncionarioController extends Controller
{
    // GET /api/v1/funcionarios
    public function index()
    {
        // traz info de pessoa também
        $funcionarios = Funcionario::with('pessoa')->get();
        return response()->json($funcionarios, 200);
    }

    // POST /api/v1/funcionarios
    public function store(Request $request)
    {
        $data = $request->validate([
            'id'             => 'required|integer|exists:pessoa,id|unique:funcionario,id',
            'nivel_acesso'   => 'required|in:ADMINISTRADOR,CONSULTOR',
            'salario'        => 'nullable|numeric',
            'data_contratacao'=> 'nullable|date'
        ]);

        $funcionario = Funcionario::create($data);
        return response()->json($funcionario->load('pessoa'), 201);
    }

    // GET /api/v1/funcionarios/{id}
    public function show($id)
    {
        $funcionario = Funcionario::with('pessoa')->find($id);
        if (! $funcionario) {
            return response()->json(['error'=>'Funcionário não encontrado'],404);
        }
        return response()->json($funcionario, 200);
    }

    // PUT /api/v1/funcionarios/{id}
    public function update(Request $request, $id)
    {
        $funcionario = Funcionario::find($id);
        if (! $funcionario) {
            return response()->json(['error'=>'Funcionário não encontrado'],404);
        }
        $data = $request->validate([
            'nivel_acesso'   => 'sometimes|required|in:ADMINISTRADOR,CONSULTOR',
            'salario'        => 'nullable|numeric',
            'data_contratacao'=> 'nullable|date'
        ]);
        $funcionario->update($data);
        return response()->json($funcionario->load('pessoa'), 200);
    }

    // DELETE /api/v1/funcionarios/{id}
    public function destroy($id)
    {
        $funcionario = Funcionario::find($id);
        if (! $funcionario) {
            return response()->json(['error'=>'Funcionário não encontrado'],404);
        }
        $funcionario->delete();
        return response()->json(['message'=>'Excluído'],200);
    }
}
