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
            ->whereDoesntHave('user'); // Apenas pessoas ainda sem usuário

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        return response()->json([
            'data' => $query->orderBy('name')->get()
                ->map(fn ($person) => [
                    'id' => $person->id,
                    'name' => $person->name,
                    'email' => $person->email,
                ]),
        ]);
    }

}
