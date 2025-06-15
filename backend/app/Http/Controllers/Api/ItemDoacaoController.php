<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemDoacao;

class ItemDoacaoController extends Controller
{
    // Exemplo mínimo se for expor:
    public function index()
    {
        return response()->json(ItemDoacao::with(['doacao','produto'])->get(), 200);
    }
    // store, show, update, destroy podem ser implementados se necessário
}
