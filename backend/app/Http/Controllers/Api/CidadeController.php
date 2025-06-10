<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cidade;

class CidadeController extends Controller
{
    // GET /api/v1/cidades
    // opcionalmente aceita ?estado_id=XX para filtrar
    public function index(Request $request)
    {
        $query = Cidade::query();
        if ($request->has('estado_id')) {
            $query->where('estado_id', $request->input('estado_id'));
        }
        $cidades = $query->get();
        return response()->json($cidades, 200);
    }
}
