<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
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
        //$this->authorize('viewAny', User::class); // 🔐 Autoriza listagem de usuários

        $query = User::with(['roles', 'person']);

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

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);

        // Transforma os papéis para exibição
        $users->getCollection()->transform(function ($user) {
            $user->roles = $user->roles->map(fn($role) => [
                'id' => $role->id,
                'name' => $role->name,
            ]);
            return $user;
        });

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
     * Cria um novo usuário e atribui papéis.
     */
    public function store(StoreUserRequest $request)
    {
        //$this->authorize('create', User::class); // 🔐 Autoriza criação

        $user = User::create([
            'person_id' => $request->person_id,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->syncRoles($request->roles);

        return response()->json(['message' => 'Usuário criado com sucesso']);
    }

    /**
     * Exibe os dados de um usuário específico.
     */
    public function show($id)
    {
        $user = User::with(['person', 'roles'])->findOrFail($id);

       // $this->authorize('view', $user); //Autoriza visualização individual

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Retorna dados do usuário para edição.
     */
    public function edit($id)
    {
        $user = User::with('person')->findOrFail($id);

        //$this->authorize('update', $user); 

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Atualiza os dados e papéis de um usuário.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //$this->authorize('update', $user); // 🔐 Autoriza atualização

        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        if ($request->has('name')) {
            $user->person()->update(['name' => $request->name]);
        }

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            'user' => $user->load('person', 'roles'),
        ]);
    }

    /**
     * Remove um usuário do sistema.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        //$this->authorize('delete', $user); // 🔐 Autoriza exclusão

        $user->delete();

        return response()->json(['message' => 'Usuário deletado com sucesso.']);
    }

    /**
     * Sincroniza papéis atribuídos a um usuário.
     */
    public function syncRoles(Request $request, User $user)
    {
        //$this->authorize('update', $user); // 🔐 Autoriza vincular papéis

        $request->validate([
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        $user->syncRoles($request->roles);

        return response()->json(['message' => 'Papéis atualizados com sucesso']);
    }
}
