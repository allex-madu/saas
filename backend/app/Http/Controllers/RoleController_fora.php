<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Retorna todos os papéis no formato simplificado para dropdown.
     */
    public function index()
    {
        // Busca todos os papéis do sistema
        $roles = Role::all();

        // Formata o resultado no padrão { value, label } para uso em selects
        return response()->json([
            'roles' => $roles->map(function($role) {
                return [
                    'value' => $role->id,
                    'label' => $role->name,
                ];
            })
        ]);
    }
    
}
