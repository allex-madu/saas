<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        $users = $query->paginate($request->get('per_page', 10));

        $roles = Role::all();

        return response()->json([
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'person_id' => 'required|exists:people,id|unique:users,person_id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::create([
            'person_id' => $validated['person_id'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        try {
                $user->assignRole($validated['role']);
            } catch (\Exception $e) {
                report($e); // loga o erro
                return response()->json(['message' => 'Usuário criado, mas erro ao atribuir papel'], 500);
            }

            return response()->json(['message' => 'Usuário criado com sucesso'], 201);
    }
    
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
