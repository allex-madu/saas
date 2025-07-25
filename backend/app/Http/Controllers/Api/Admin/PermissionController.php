<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Lista as permissões com paginação e busca opcional.
     */
    public function index()
    {
        //$this->authorize('viewAny', Permission::class); // Autoriza visualização geral

        $perPage = request()->get('per_page', 10);
        $search = request()->get('search');

        // Consulta com filtro opcional por nome
        $query = Permission::select('id', 'name', 'description', 'guard_name', 'created_at', 'updated_at')
            ->orderBy('name');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $permissions = $query->paginate($perPage);

        // Retorno padronizado com dados, metadados e links de paginação
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

    /**
     * Cria uma nova permissão.
     */
    public function store(Request $request)
    {
        //$this->authorize('create', Permission::class); // Autoriza criação

        // Validação dos dados recebidos
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'description' => 'nullable|string',
        ]);

        // Criação da permissão
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

    /**
     * Exibe os dados de uma permissão específica.
     */
    public function show(Permission $permission)
    {
        //$this->authorize('view', $permission); // Autoriza visualização específica

        return response()->json([
            'permission' => $permission,
        ]);
    }

    /**
     * Atualiza uma permissão existente.
     */
    public function update(Request $request, Permission $permission)
    {
        //$this->authorize('update', $permission); // Autoriza atualização

        // Validação com exceção para o nome atual
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string',
        ]);

        // Atualização da permissão
        $permission->update($validated);

        return response()->json([
            'message' => 'Permissão atualizada com sucesso.',
            'permission' => $permission
        ]);
    }

    /**
     * Remove uma permissão do sistema.
     */
    public function destroy(Permission $permission)
    {
        //$this->authorize('delete', $permission); // Autoriza exclusão

        $permission->delete();

        return response()->json(['message' => 'Permissão removida com sucesso.']);
    }

    /**
     * Retorna as permissões agrupadas por módulo para uso em árvore no frontend.
     */
    public function grouped()
    {
        //$this->authorize('viewAny', Permission::class); // Autoriza visualização geral

        $permissions = \Spatie\Permission\Models\Permission::all();

        $grouped = [];

        // Agrupa as permissões com base na convenção "modulo.acao"
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $group = $parts[0] ?? 'outros';

            if (!isset($grouped[$group])) {
                $grouped[$group] = [
                    'title' => ucfirst($group),
                    'name' => $group,
                    'children' => [],
                ];
            }

            $grouped[$group]['children'][] = [
                'name' => $permission->name,
                'description' => $permission->description ?? $permission->name,
            ];
        }

        // Converte em array indexado para facilitar iteração no frontend
        return response()->json(array_values($grouped));
    }
}
