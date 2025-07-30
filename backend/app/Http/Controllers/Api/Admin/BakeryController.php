<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Bakery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class BakeryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Bakery::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%")
                ->orWhere('nif', 'like', "%{$search}%");
        }

        $bakeries = $query->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'data' => $bakeries->items(),
            'meta' => [
                'total' => $bakeries->total(),
                'current_page' => $bakeries->currentPage(),
                'last_page' => $bakeries->lastPage(),
                'per_page' => $bakeries->perPage(),
            ],
        ]);
    }


    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'slug' => 'required|string|unique:bakeries,slug',
    //         'nif' => 'nullable|string|unique:bakeries,nif',
    //         'phone' => 'nullable|string|max:20',
    //         'admin_name' => 'required|string|max:255',
    //         'admin_email' => 'required|email|unique:users,email',
    //         'admin_password' => 'required|string|min:8|confirmed',
    //     ]);

    //     $creatorId = auth()->id();

    //     DB::transaction(function () use ($data, $creatorId) {
    //         // Cria a padaria
    //         $bakery = Bakery::create([
    //             'name' => $data['name'],
    //             'slug' => $data['slug'],
    //             'nif' => $data['nif'] ?? null,
    //             'phone' => $data['phone'] ?? null,
    //             'created_by' => $creatorId,
    //             'trial_until' => now()->addDays(7),
    //         ]);

    //         // Cria a pessoa com nome e e-mail do admin
    //         $person = \App\Models\Person::create([
    //             'name' => $data['admin_name'],
    //             'email' => $data['admin_email'],
    //             'nif' => $data['nif'] ?? null,
    //         ]);

    //         // Cria o usuário e vincula à pessoa
    //         $admin = User::create([
    //             'person_id' => $person->id,
    //             'email' => $data['admin_email'],
    //             'password' => Hash::make($data['admin_password']),
    //             'bakery_id' => $bakery->id,
    //         ]);

    //         $admin->assignRole('admin');
    //         $bakery->update(['admin_id' => $admin->id]);
    //     });

    //     return response()->json(['message' => 'Padaria e administrador criados com sucesso.'], 201);
    // }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:bakeries,slug',
            'nif' => 'nullable|string|unique:bakeries,nif',
            'phone' => 'nullable|string|max:20',

            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($data) {
            // Cria padaria
            $bakery = Bakery::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'nif' => $data['nif'] ?? null,
                'phone' => $data['phone'] ?? null,
                'created_by' => auth()->id(),
                'trial_until' => now()->addDays(7),
            ]);

            // Cria pessoa
            $person = $bakery->admin()->create([
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'nif' => $data['nif'] ?? null,
            ])->person()->create([
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
            ]);

            // Cria usuário e associa à pessoa e padaria
            $user = $person->user()->create([
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
                'bakery_id' => $bakery->id,
            ]);

            // Define o usuário como admin da padaria
            $bakery->update(['admin_id' => $user->id]);

            // Atribui papel
            $user->assignRole('admin');
        });

        return response()->json(['message' => 'Padaria criada com sucesso.'], 201);
    }



  

    public function update(Request $request, $id)
    {
        $bakery = Bakery::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                Rule::unique('bakeries', 'slug')->ignore($bakery->id),
            ],
            'nif' => [
                'nullable',
                'string',
                Rule::unique('bakeries', 'nif')->ignore($bakery->id),
            ],
            'phone' => 'nullable|string|max:20',
            'admin_name' => 'required|string|max:255',
            'admin_email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($bakery->admin_id),
            ],
            'admin_password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($bakery, $data, $request) {
            // Atualiza padaria
            $bakery->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'nif' => $data['nif'] ?? null,
                'phone' => $data['phone'] ?? null,
            ]);

            // Atualiza pessoa
            $person = $bakery->admin->person;
            $person->update([
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'nif' => $data['nif'] ?? null,
            ]);

            // Atualiza usuário
            $user = $bakery->admin;
            $user->email = $data['admin_email'];

            if ($request->filled('admin_password')) {
                $user->password = Hash::make($request->input('admin_password'));
            }

            $user->save();
        });

        return response()->json(['message' => 'Padaria atualizada com sucesso.']);
    }





    public function show($id)
    {
        $bakery = Bakery::with(['admin.person'])->findOrFail($id);

        return response()->json([
            'id' => $bakery->id,
            'name' => $bakery->name,
            'slug' => $bakery->slug,
            'nif' => $bakery->nif,
            'phone' => $bakery->phone,
            'admin_name' => optional(optional($bakery->admin)->person)->name,
            'admin_email' => optional($bakery->admin)->email,
        ]);
    }





}
