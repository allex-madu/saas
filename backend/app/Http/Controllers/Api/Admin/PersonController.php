<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        // Garante que o usuário tem permissão para listar pessoas (via Policy)
        //$this->authorize('viewAny', Person::class);

        // Inicia consulta apenas com pessoas que ainda não têm usuário vinculado
        $query = Person::query()
            ->whereDoesntHave('user');

        // Se houver termo de busca, aplica filtro por nome ou e-mail
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Ordena e executa consulta
        $persons = $query->orderBy('name')->get();

        // Retorna apenas os campos necessários
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


    public function city()
    {
        return $this->belongsTo(City::class);
    }

}
