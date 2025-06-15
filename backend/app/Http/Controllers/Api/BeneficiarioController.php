<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beneficiario;
use App\Models\Pessoa;

class BeneficiarioController extends Controller
{
    // GET /api/v1/beneficiarios
    public function index()
    {
        $beneficiarios = Beneficiario::with('pessoa')->get();
        return response()->json($beneficiarios, 200);
    }

    // POST /api/v1/beneficiarios
    public function store(Request $request)
    {
        $data = $request->validate([
            'id'          => 'required|integer|exists:pessoa,id|unique:beneficiario,id',
            'limite'      => 'nullable|integer|min:0',
            'cartao_benef'=> 'nullable|string|max:100',
            'status_conta'=> 'required|in:CONTA_APROVADA,CONTA_NEGADA,CONTA_EM_ANALISE'
        ]);

        // Verificação adicional: só ADM pode aprovar
        $usuarioLogado = $request->user();
        if ($data['status_conta'] === 'CONTA_APROVADA' &&
            $usuarioLogado->funcionario->nivel_acesso !== 'ADMINISTRADOR') {
            return response()->json(['error'=>'Somente administrador pode aprovar conta'],403);
        }

        $beneficiario = Beneficiario::create($data);
        return response()->json($beneficiario->load('pessoa'), 201);
    }

    // GET /api/v1/beneficiarios/{id}
    public function show($id)
    {
        $benef = Beneficiario::with('pessoa')->find($id);
        if (! $benef) {
            return response()->json(['error'=>'Beneficiário não encontrado'],404);
        }
        return response()->json($benef, 200);
    }

    // PUT /api/v1/beneficiarios/{id}
    public function update(Request $request, $id)
    {
        $benef = Beneficiario::find($id);
        if (! $benef) {
            return response()->json(['error'=>'Beneficiário não encontrado'],404);
        }

        $data = $request->validate([
            'limite'      => 'sometimes|integer|min:0',
            'cartao_benef'=> 'sometimes|string|max:100',
            'status_conta'=> 'sometimes|in:CONTA_APROVADA,CONTA_NEGADA,CONTA_EM_ANALISE'
        ]);

        // Se alterar status para APROVADA, só ADM pode
        if (isset($data['status_conta']) && $data['status_conta'] === 'CONTA_APROVADA') {
            $usuarioLogado = $request->user();
            if ($usuarioLogado->funcionario->nivel_acesso !== 'ADMINISTRADOR') {
                return response()->json(['error'=>'Somente administrador pode aprovar conta'],403);
            }
        }

        $benef->update($data);
        return response()->json($benef->load('pessoa'), 200);
    }

    // DELETE /api/v1/beneficiarios/{id}
    public function destroy($id)
    {
        $benef = Beneficiario::find($id);
        if (! $benef) {
            return response()->json(['error'=>'Beneficiário não encontrado'],404);
        }
        $benef->delete();
        return response()->json(['message'=>'Excluído'],200);
    }
}
