<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Model;

class SetActiveBakeryScope
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && session()->has('active_bakery_id')) {
            $bakeryId = session('active_bakery_id');

            // Define global para todos os modelos que usarem esse trait
            Model::macro('scopeFromActiveBakery', function ($query) use ($bakeryId) {
                return $query->where('bakery_id', $bakeryId);
            });
        }

        return $next($request);
    }
}
