<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Lista os papéis com paginação e busca opcional.
     */
    public function index()
    {
        $this->authorize('viewAny', Role::class);

        $perPage = request()->get('per_page', 10);
        $search = request()->get('search');

        $query = Role::select('id', 'name', 'description', 'created_at')
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

    /**
     * Cria um novo papel com permissões opcionais.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'guard_name' => 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return response()->json(['role' => $role->load('permissions')], 201);
    }

    /**
     * Retorna os dados de um papel específico.
     */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        $this->authorize('view', $role);

        return response()->json(['role' => $role]);
    }

    /**
     * Atualiza um papel existente, incluindo suas permissões.
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

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

    /**
     * Remove um papel do sistema.
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        $role->delete();

        return response()->json(['message' => 'Papel removido com sucesso.']);
    }

    /**
     * Retorna todos os papéis (sem paginação) para uso em selects.
     */
    public function listAll()
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::select('id', 'name')->orderBy('name')->get();

        return response()->json([
            'roles' => $roles,
        ]);
    }

}
