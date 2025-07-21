<?php

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    
    public function index()
    {
        $perPage = request()->get('per_page', 10);
        $search = request()->get('search');

        $query = Permission::select('id', 'name', 'description', 'guard_name', 'created_at', 'updated_at')
            ->orderBy('name');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $permissions = $query->paginate($perPage);

        return response()->json([
            'data' => $permissions->items(),
            'meta' => [
                'total' => $permissions->total(),
                'per_page' => $permissions->perPage(),
                'current_page' => $permissions->currentPage(),
                'last_page' => $permissions->lastPage(),
                'from' => $permissions->firstItem(),
                'to' => $permissions->lastItem(),
            ],
            'links' => [
                'next' => $permissions->nextPageUrl(),
                'prev' => $permissions->previousPageUrl(),
            ],
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'description' => 'nullable|string',
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'guard_name' => 'web',
        ]);

        return response()->json([
            'message' => 'Permissão criada com sucesso.',
            'permission' => $permission
        ], 201);
    }


    public function show(Permission $permission)
    {
        return response()->json([
            'permission' => $permission,
        ]);
    }



    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string',
        ]);

        $permission->update($validated);

        return response()->json([
            'message' => 'Permissão atualizada com sucesso.',
            'permission' => $permission
        ]);
    }


    
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json(['message' => 'Permissão removida com sucesso.']);
    }


    public function grouped()
    {
        $permissions = \Spatie\Permission\Models\Permission::all();

        $grouped = [];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $group = $parts[0] ?? 'Outros';

            if (!isset($grouped[$group])) {
                $grouped[$group] = [
                    'title' => ucfirst($group), // ex: 'Usuários'
                    'children' => [],
                ];
            }

            $grouped[$group]['children'][] = [
                'name' => $permission->name,
                'description' => $permission->description ?? $permission->name,
            ];
        }

        return response()->json(array_values($grouped));
    }

}
