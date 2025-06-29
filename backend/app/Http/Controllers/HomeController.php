<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Apenas retorna a view da página inicial pública
        return view('welcome');
    }
}