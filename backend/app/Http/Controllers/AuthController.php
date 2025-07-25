<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Realiza o login do usuário.
     */
    public function login(Request $request)
    {
        // Valida as credenciais
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tenta autenticar o usuário
        if (Auth::attempt($credentials)) 
        {
            // Regenera a sessão para proteger contra fixation
            $request->session()->regenerate();

            // Carrega o relacionamento com a pessoa
            $user = $request->user()->load('person');

            // Retorna os dados do usuário autenticado
            return response()->json([
                'message' => 'Login realizado com sucesso!',
                'user' => $user,
            ]);
        }

        // Credenciais inválidas
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    /**
     * Realiza o logout e invalida a sessão.
     */
    public function logout(Request $request)
    {
        // Faz logout no guard padrão (web)
        Auth::guard('web')->logout();

        // Invalida a sessão atual
        $request->session()->invalidate();

        // Gera novo token CSRF
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    /**
     * Retorna os dados do usuário autenticado.
     */
    public function user(Request $request)
    {
        // Carrega o relacionamento com a pessoa
        $user = $request->user()->load('person');

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'person' => $user->person,
            'is_super_admin' => $user->is_super_admin,
            'roles' => $user->getRoleNames(), // Inclui os papéis do usuário
        ]);
    }
}
