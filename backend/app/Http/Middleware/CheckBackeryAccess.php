<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckBackeryAccess
{
    /**
     * Verifica se o usuário tem permissão para acessar uma padaria específica
     * (com base no campo `backery_id`) ou se é super administrador.
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();

        // Usuário não autenticado
        if (!$user) {
            abort(401, 'Não autenticado.');
        }
        

        // Se for super admin, permite acesso sem restrições
        if ($user instanceof \App\Models\User && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return $next($request);
        }

        // Verifica se o ID da padaria na rota ou request bate com o do usuário
        $bakeryId = $request->route('bakery_id') ?? $request->input('bakery_id');

        // Só aplica validação se o bakery_id vier na rota ou query
        if (!is_null($bakeryId)) {
            // Se usuário não tiver padaria ou padaria não coincidir, bloqueia
            if (is_null($user->backery_id) || $user->backery_id != $bakeryId) {
                abort(403, 'Acesso negado: padaria inválida.');
            }
        }
        


        // Continua a requisição normalmente
        return $next($request);
    }
}
