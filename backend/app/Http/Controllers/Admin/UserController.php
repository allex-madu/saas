<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'person']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                  ->orWhereHas('person', fn($q) => $q->where('name', 'like', "%$search%"));
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        $users = $query->paginate($request->get('perPage', 10));

        $roles = Role::all();

        return response()->json([
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return response()->json(['message' => 'Usuário criado com sucesso', 'user' => $user->load('roles')]);
    }

    // public function show(User $user)
    // {
    //     return response()->json($user->load('roles', 'person'));
    // }
    public function show($id)
{
    $user = User::with(['person', 'roles'])->findOrFail($id);

    return response()->json([
        'user' => $user,
    ]);
}

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->only(['email', 'password']));

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return response()->json(['message' => 'Usuário atualizado com sucesso', 'user' => $user->load('roles')]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'Usuário excluído com sucesso']);
    }

    public function syncRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        $user->syncRoles($request->roles);

        return response()->json(['message' => 'Papéis atualizados com sucesso']);
    }
}
