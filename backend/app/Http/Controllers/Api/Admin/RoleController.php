<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->get('per_page', 10);
        $search = request()->get('search');

        $query = Role::select('id', 'name', 'description', 'created_at') // ← description incluído aqui
            ->orderBy('name');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $roles = $query->paginate($perPage);

        return response()->json([
            'data' => $roles->items(),
            'meta' => [
                'total' => $roles->total(),
                'per_page' => $roles->perPage(),
                'current_page' => $roles->currentPage(),
                'last_page' => $roles->lastPage(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastItem(),
            ],
            'links' => [
                'next' => $roles->nextPageUrl(),
                'prev' => $roles->previousPageUrl(),
            ],
        ]);
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'description' => 'nullable|string', // ← adicione isso
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null, // ← adicione isso
            'guard_name' => 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }
        return response()->json(['role' => $role->load('permissions')], 201);
    }


    

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json(['role' => $role]);
    }


   

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'guard_name' => 'web',
        ]);

        $role->refresh();

        $role->syncPermissions($validated['permissions'] ?? []);

        return response()->json(['role' => $role->load('permissions')]);
    }


   
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(['message' => 'Papel removido com sucesso.']);
    }
}
