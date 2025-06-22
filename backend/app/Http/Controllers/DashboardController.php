<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Apenas retorna a view do dashboard para usuários logados
        return view('dashboard');
    }
}