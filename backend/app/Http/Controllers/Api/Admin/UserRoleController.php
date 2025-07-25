<?php 

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index()
    {
        // Verifica se o usuário tem permissão para visualizar a lista de usuários
        $this->authorize('viewAny', Person::class);

        // Carrega usuários com seus papéis e dados pessoais
        $users = User::with('roles', 'person')->get();

        // Carrega todos os papéis disponíveis
        $roles = Role::all();

        return response()->json([
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
