<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        // Cria a consulta base
        $query = Person::query()
            ->whereDoesntHave('user');  // Só pessoas sem usuário vinculado

        // Verifica se a busca foi preenchida
        if ($request->filled('search')) {
            $search = $request->search;

            // Aplica o filtro de busca para nome ou email dentro da consulta
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Executa a consulta com os filtros aplicados
        
        $persons = $query->orderBy('name')->get();
        return response()->json([
            'data' => $persons->map(function ($person) {
                return [
                    'id' => $person->id,
                    'name' => $person->name,
                    'email' => $person->email,
                ];
            }),
        ]);
    }
}
