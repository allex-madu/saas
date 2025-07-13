<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckBackeryAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Não autenticado.');
        }

        Log::info('User class: ' . get_class($user));
        Log::info('User data: ' . json_encode($user));

        if ($user instanceof \App\Models\User) {
            Log::info('User is instance of App\Models\User');
            if (method_exists($user, 'isSuperAdmin')) {
                Log::info('User has method isSuperAdmin');
                if ($user->isSuperAdmin()) {
                    Log::info('User is super admin');
                    return $next($request);
                }
            } else {
                Log::warning('User does NOT have method isSuperAdmin');
            }
        } else {
            Log::warning('User is NOT instance of App\Models\User');
        }

        $bakeryId = $request->route('bakery_id') ?? $request->input('bakery_id');

        if ($bakeryId && $bakeryId != $user->backery_id) {
            abort(403, 'Acesso negado: padaria inválida.');
        }

        return $next($request);
    }
}
