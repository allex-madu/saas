<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Bakery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class BakeryController extends Controller
{
    // Lista padarias com paginação e busca
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

    // Criação de padaria com admin vinculado
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
            // Cria padaria sem admin inicialmente
            $bakery = Bakery::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'nif' => $data['nif'] ?? null,
                'phone' => $data['phone'] ?? null,
                'created_by' => auth()->id(),
                'trial_until' => now()->addDays(7),
            ]);

            // Cria pessoa e usuário vinculados
            $person = \App\Models\Person::create([
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
            ]);

            $user = $person->user()->create([
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
                'bakery_id' => $bakery->id,
            ]);

            // Atualiza padaria com admin
            $bakery->update(['admin_id' => $user->id]);

            // Atribui papel
            $user->assignRole('admin');
        });

        return response()->json(['message' => 'Padaria criada com sucesso.'], 201);
    }

    // Atualização de dados da padaria + admin (e-mail/senha)
    public function update(Request $request, $id)
    {
        $bakery = Bakery::with('admin')->findOrFail($id);

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
            'admin_email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($bakery->admin_id),
            ],
            'admin_password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($bakery, $data) {
            // Atualiza padaria
            $bakery->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'nif' => $data['nif'] ?? null,
                'phone' => $data['phone'] ?? null,
            ]);

            // Atualiza admin
            $admin = $bakery->admin;
            if ($admin) {
                $admin->update(['email' => $data['admin_email']]);

                if (!empty($data['admin_password'])) {
                    $admin->update([
                        'password' => Hash::make($data['admin_password']),
                    ]);
                }
            }
        });

        return response()->json(['message' => 'Padaria atualizada com sucesso.']);
    }

    // Detalhes para tela de visualização e formulário
    public function show($id)
    {
        $bakery = Bakery::with(['admin.person'])->findOrFail($id);

        return response()->json([
            'id' => $bakery->id,
            'name' => $bakery->name,
            'slug' => $bakery->slug,
            'nif' => $bakery->nif,
            'phone' => $bakery->phone,
            'created_at' => $bakery->created_at,
            'updated_at' => $bakery->updated_at,

            'admin' => [
                'name' => optional(optional($bakery->admin)->person)->name,
                'email' => optional($bakery->admin)->email,
            ],

            'admin_name' => optional(optional($bakery->admin)->person)->name,
            'admin_email' => optional($bakery->admin)->email,
        ]);
    }

    // Exclusão simples
    public function destroy($id)
    {
        $bakery = Bakery::findOrFail($id);

        // Validações extras (ex: impedir exclusão se for padaria ativa) podem ser incluídas aqui

        $bakery->delete();

        return response()->json(['message' => 'Padaria removida com sucesso.']);
    }
}
