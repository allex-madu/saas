<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Lista usuários com filtros por nome, email ou papel.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::with(['roles', 'person']);

        // Filtro por nome/email/nickname (usuário ou pessoa)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                  ->orWhereHas('person', function ($personQ) use ($search) {
                      $personQ->where('name', 'like', "%$search%")
                              ->orWhere('nickname', 'like', "%$search%")
                              ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        // Filtro por papel
        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        // Filtro por padaria
        if ($request->filled('bakery_id')) {
            $query->whereHas('bakeries', function ($q) use ($request) {
                $q->where('bakery_id', $request->bakery_id);
            });
        }

        // Paginação
        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);

        // Formata papéis para exibição
        $users->getCollection()->transform(function ($user) {
            $user->roles = $user->roles->map(fn($role) => [
                'id' => $role->id,
                'name' => $role->name,
            ]);
            return $user;
        });

        // Lista de papéis (extra para filtro ou formulário)
        $roles = Role::select('id', 'name')->get();

        return response()->json([
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
            'links' => [
                'first' => $users->url(1),
                'last' => $users->url($users->lastPage()),
                'prev' => $users->previousPageUrl(),
                'next' => $users->nextPageUrl(),
            ],
            'extra' => [
                'roles' => $roles,
            ],
        ]);
    }

    /**
     * Cria um novo usuário com pessoa vinculada e papéis.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        // Criação da pessoa
        $person = Person::create([
            'name' => $request->name,
            'nickname' => $request->nickname ?? null,
            'nif' => $request->nif ?? null, 
            'phone' => $request->phone ?? null,
            'address' => $request->address ?? null,
            'city_id' => $request->city_id ?? null,
            'active' => $request->active ?? true,
            'email' => $request->email,
        ]);

        // Criação do usuário
        $user = User::create([
            'person_id' => $person->id,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Atribuição de papéis
        $user->syncRoles($request->roles);

        return response()->json([
            'message' => 'Usuário criado com sucesso',
            'user' => $user->load('person', 'roles'),
        ]);
    }

    /**
     * Retorna os dados de um usuário específico.
     */
    public function show($id)
    {
        $user = User::with(['person', 'roles'])->findOrFail($id);

        $this->authorize('view', $user);

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Retorna os dados para o formulário de edição.
     */
    public function edit($id)
    {
        // Inclui cidade e estado da pessoa
        $user = User::with(['person.city.state', 'roles'])->findOrFail($id);

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Atualiza dados do usuário, pessoa e papéis.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user); 

        // Atualiza campos do próprio usuário
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Atualiza dados da pessoa
        $user->person()->update([
            'name' => $request->name,
            'nif' => $request->nif,
            'phone' => $request->phone,
            'address' => $request->address,
            'city_id' => $request->city_id,
        ]);

        // Atualiza papéis se enviados
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            'user' => $user->load('person', 'roles'),
        ]);
    }

    /**
     * Exclui um usuário da base.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $this->authorize('delete', $user);

        $user->delete();

        return response()->json(['message' => 'Usuário deletado com sucesso.']);
    }

    /**
     * Sincroniza papéis de um usuário (rota auxiliar).
     */
    public function syncRoles(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        $user->syncRoles($request->roles);

        return response()->json(['message' => 'Papéis atualizados com sucesso']);
    }
}
