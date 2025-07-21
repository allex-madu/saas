<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Busca todos os papéis
        $roles = Role::all(); // Se estiver usando Spatie

        // Retorna os papéis em formato JSON
        return response()->json([
            'roles' => $roles->map(function($role) {
                return [
                    'value' => $role->id,
                    'label' => $role->name,
                ];
            })
        ]);
    }

    

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return response()->json(['role' => $role->load('permissions')], 201);
    }

    

    public function show(Role $role)
    {
        return response()->json([
            'role' => $role->load('permissions'),
        ]);
    }

   

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $validated['name']]);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return response()->json(['role' => $role->load('permissions')]);
    }

   
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(['message' => 'Papel removido com sucesso.']);
    }
}
