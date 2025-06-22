<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoriaProduto;

class CategoriaProdutoController extends Controller
{
    public function index()
    {
        return response()->json(CategoriaProduto::with(['categoria','produto'])->get(), 200);
    }
}
