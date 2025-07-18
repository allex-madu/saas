<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Realiza o login do usuário e retorna os dados.
     */
    public function login(Request $request)
    {
        // Validação dos dados recebidos
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tenta autenticar com as credenciais
        if (Auth::attempt($credentials)) {
            // Garante que outras sessões do mesmo usuário sejam encerradas
            Auth::logoutOtherDevices($request->password);

            // Regenera a sessão atual (proteção contra fixation)
            $request->session()->regenerate();

            // Carrega relações do usuário autenticado
            $user = $request->user()->load('person');

            return response()->json([
                'message' => 'Login realizado com sucesso!',
                'user' => $user,
            ]);
        }

        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    /**
     * Realiza o logout da sessão atual.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        // Invalida a sessão
        $request->session()->invalidate();

        // Regenera o token CSRF
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    /**
     * Retorna os dados do usuário autenticado.
     */
    public function user(Request $request)
{
    $user = $request->user()?->load('person', 'roles'); // ← Adiciona o eager loading

    if (!$user) {
        return response()->json(['message' => 'Não autenticado'], 401);
    }

    // Retorna os dados com roles como objetos
    return response()->json([
        'id' => $user->id,
        'email' => $user->email,
        'person' => $user->person,
        'is_super_admin' => $user->is_super_admin,
        'roles' => $user->roles->map(fn($role) => [
            'id' => $role->id,
            'name' => $role->name,
        ]),
    ]);
}

}
