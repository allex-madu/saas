<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) 
        {
            $request->session()->regenerate();
            $user = $request->user()->load('person');
            return response()->json([
                'message' => 'Login realizado com sucesso!',
                'user' => $user,
            ]);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function user(Request $request)
    {
        $user = $request->user()->load('person');

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'person' => $user->person,
            'is_super_admin' => $user->is_super_admin,
            'roles' => $user->getRoleNames(), // <--- aqui adiciona as roles
        ]);
    }


}
