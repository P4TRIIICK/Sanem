<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Hash;

class AutenticacaoController extends Controller
{
    // POST /api/v1/register
    public function register(Request $request)
    {
        $data = $request->validate([
            'nome'     => 'required|string|max:255',
            'cpf'      => 'required|string|max:20|unique:pessoa,cpf',
            'email'    => 'required|string|email|max:255|unique:pessoa,email',
            'password' => 'required|string|min:6|confirmed',
            // restante dos campos de pessoa (rg, genero, etc)
        ]);

        // Cria Pessoa como “usuário”:
        $pessoa = Pessoa::create([
            'nome'      => $data['nome'],
            'cpf'       => $data['cpf'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            // complete os outros campos obrigatórios
        ]);

        $token = $pessoa->createToken('token')->plainTextToken;

        return response()->json([
            'usuario' => $pessoa,
            'token'   => $token
        ], 201);
    }

    // POST /api/v1/login
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string'
        ]);

        $pessoa = Pessoa::where('email', $data['email'])->first();
        if (! $pessoa || ! Hash::check($data['password'], $pessoa->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = $pessoa->createToken('token')->plainTextToken;
        return response()->json([
            'usuario' => $pessoa,
            'token'   => $token
        ], 200);
    }

    // POST /api/v1/logout
    public function logout(Request $request)
    {
        // Revoga todos os tokens do usuário logado
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Deslogado com sucesso'], 200);
    }
}
//Obs.: os campos password e rememberToken só existem se você tiver adicionado no Model Pessoa a coluna password no banco (e no migration de pessoa). Se não adicionou, crie isso ou adapte para usar outra tabela de usuários.