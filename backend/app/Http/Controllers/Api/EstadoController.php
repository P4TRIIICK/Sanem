<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estado;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    // GET /api/v1/estados
    public function index()
    {
        $estados = Estado::all();
        return response()->json($estados, 200);
    }

    // POST /api/v1/estados
    public function store(Request $request)
    {
        // Valida que 'nome' seja obrigatório
        $data = $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        // Cria o novo estado
        $estado = Estado::create($data);

        // Retorna o estado criado com código HTTP 201
        return response()->json($estado, 201);
    }
}
