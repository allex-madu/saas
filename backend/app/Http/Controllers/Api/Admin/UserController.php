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

        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);

        // Transforma os papéis em array de objetos por usuário
        $users->getCollection()->transform(function ($user) {
            $user->roles = $user->roles->map(fn($role) => [
                'id' => $role->id,
                'name' => $role->name,
            ]);
            return $user;
        });

        $roles = Role::select('id', 'name')->get();

        return response()->json([
            'data' => $users->items(), // lista de usuários
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





    
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'person_id' => $request->person_id,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Sincroniza papéis corretamente
        $user->syncRoles($request->roles);

        return response()->json(['message' => 'Usuário criado com sucesso']);
    }

    



    public function show($id)
    {
        $user = User::with(['person', 'roles'])->findOrFail($id);

        return response()->json([
            'user' => $user,
        ]);
    }


    public function edit($id)
    {
        // Carregar o usuário com o relacionamento 'person'
        $user = User::with('person')->findOrFail($id);  

        return response()->json([
            'user' => $user,
        ]);
    }




   public function update(UpdateUserRequest $request, User $user)
    {
        // Atualiza e-mail
        $user->email = $request->email;

        // Atualiza a senha apenas se enviada
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Atualiza nome da pessoa, se enviado
        if ($request->has('name')) {
            $user->person()->update(['name' => $request->name]);
        }

        // Atualiza papéis se enviados
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            'user' => $user->load('person', 'roles'),
        ]);
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuário deletado com sucesso.']);
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
