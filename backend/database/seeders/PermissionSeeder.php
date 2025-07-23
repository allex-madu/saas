<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $flatPermissions = [];

        // 1. Permissões diretas
        foreach (Permission::PERMISSIONS as $perm) {
            $flatPermissions[] = $perm;
        }

        // 2. Permissões em árvore
        $tree = Permission::treeStructure();
        $flatPermissions = array_merge($flatPermissions, $this->flattenTree($tree));

        // 3. Inserção
        foreach ($flatPermissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name']],
                [
                    'description' => $perm['description'] ?? null,
                    'guard_name' => 'web',
                ]
            );
        }
    }

    private function flattenTree(array $nodes): array
    {
        $permissions = [];

        foreach ($nodes as $node) {
            if (isset($node['items'])) {
                $permissions = array_merge($permissions, $this->flattenTree($node['items']));
            } elseif (isset($node['name'])) {
                $permissions[] = [
                    'name' => $node['name'],
                    'description' => $node['description'] ?? null,
                ];
            }
        }

        return $permissions;
    }
}
