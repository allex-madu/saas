<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        $query = Person::query()
            ->whereDoesntHave('user'); // Só pessoas sem usuário

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhereHas('user', fn ($userQ) =>
                    $userQ->where('email', 'like', "%{$search}%")
                );
            });
        }

        // Retornar o email do user junto (para exibir no combobox)
        return response()->json([
            'data' => $query->with('user:id,person_id,email')
                            ->orderBy('name')
                            ->get()
                            ->map(fn ($person) => [
                                'id' => $person->id,
                                'name' => $person->name,
                                'email' => $person->user->email ?? '',
                            ]),
        ]);
    }

}
