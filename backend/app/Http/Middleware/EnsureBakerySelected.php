<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureBakerySelected
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('bakery_id')) {
            return response()->json(['message' => 'Nenhuma padaria selecionada.'], 403);
        }

        return $next($request);
    }
}
