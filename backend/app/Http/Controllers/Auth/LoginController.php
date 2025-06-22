<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); // Vamos mover para uma pasta 'auth'
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Garanta que a URL no seu arquivo .env (APP_URL) está correta!
        // Ex: APP_URL=http://127.0.0.1:8000
        $apiUrl = config('app.url') . '/api/v1/login';

        try {
            // Fazendo a requisição interna para a API
            $response = Http::acceptJson()->post($apiUrl, [
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ]);

            // VERIFICAÇÃO ROBUSTA DA RESPOSTA
            // 1. A API respondeu com sucesso (código 2xx)?
            // 2. A resposta contém um token?
            if ($response->successful() && $response->json('token')) {
                
                $userData = $response->json('usuario');
                $user = User::find($userData['id']);

                if ($user) {
                    Auth::login($user);
                    $request->session()->regenerate();
                    // Redireciona para o dashboard após o login
                    return redirect()->intended(route('dashboard'));
                }
            }

            // Se a resposta falhou ou não continha o token, retorna com erro.
            return back()->withErrors([
                'email' => 'As credenciais fornecidas são inválidas.',
            ])->onlyInput('email');

        } catch (ConnectionException $e) {
            // Erro se a API estiver offline ou a URL estiver errada
            return back()->withErrors([
                'email' => 'Não foi possível conectar ao serviço de autenticação. Tente novamente mais tarde.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Redireciona para a home pública após o logout
        return redirect()->route('home.public');
    }
}