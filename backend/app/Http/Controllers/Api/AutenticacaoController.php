<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AutenticacaoController extends Controller
{
    /**
     * Registra um novo usuário (Pessoa) no sistema.
     * Rota: POST /api/v1/register
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'nome'              => 'required|string|max:255',
            'cpf'               => 'required|string|max:20|unique:pessoa,cpf',
            'rg'                => 'nullable|string|max:20|unique:pessoa,rg',
            'genero'            => 'nullable|string|max:50',
            'tipo_beneficiario' => 'nullable|string|max:100',
            'nascimento'        => 'nullable|date',
            'endereco_id'       => 'nullable|integer|exists:endereco,id',
            'email'             => 'required|string|email|max:255|unique:pessoa,email',
            'password'          => 'required|string|min:6|confirmed', // Exige um campo 'password_confirmation'
        ]);

        // Cria a Pessoa
        $pessoa = Pessoa::create([
            'nome'              => $data['nome'],
            'cpf'               => $data['cpf'],
            'rg'                => $data['rg'] ?? null,
            'genero'            => $data['genero'] ?? null,
            'tipo_beneficiario' => $data['tipo_beneficiario'] ?? null,
            'nascimento'        => $data['nascimento'] ?? null,
            'endereco_id'       => $data['endereco_id'] ?? null,
            'email'             => $data['email'],
            'password'          => Hash::make($data['password']),
        ]);

        // Gera o token de acesso
        $token = $pessoa->createToken('auth_token')->plainTextToken;

        return response()->json([
            'usuario' => $pessoa,
            'token'   => $token
        ], 201);
    }

    /**
     * Autentica um usuário e retorna um token.
     * Rota: POST /api/v1/login
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string'
        ]);

        $pessoa = Pessoa::where('email', $data['email'])->first();

        if (!$pessoa || !Hash::check($data['password'], $pessoa->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        // Gera o token de acesso
        $token = $pessoa->createToken('auth_token')->plainTextToken;

        return response()->json([
            'usuario' => $pessoa,
            'token'   => $token
        ], 200);
    }

    /**
     * Faz o logout do usuário, revogando o token atual.
     * Rota: POST /api/v1/logout (Requer autenticação)
     */
    public function logout(Request $request)
    {
        // Revoga o token que foi usado para autenticar a requisição atual.
        // É mais seguro que apagar todos os tokens, permitindo múltiplas sessões.
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Deslogado com sucesso'], 200);
    }
}