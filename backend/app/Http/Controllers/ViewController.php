<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    /**
     * Mostra o formulário de login.
     */
    public function showLoginForm()
    {
        return view('login'); // Aponta para resources/views/login.blade.php
    }

    /**
     * Mostra a página inicial (Home).
     */
    public function showHome()
    {
        return view('home'); // Aponta para resources/views/home.blade.php
    }
}