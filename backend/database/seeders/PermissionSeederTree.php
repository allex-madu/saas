<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeederTree extends Seeder
{
    public function run(): void
    {
        $structure = Permission::treeStructure();

        if (!is_array($structure)) {
            $this->command->error('Estrutura de permissões inválida.');
            return;
        }

        $this->createPermissionsRecursive($structure);
    }

    private function createPermissionsRecursive(array $items): void
    {
        foreach ($items as $item) {
            // Se é uma permissão, cria
            if (isset($item['name'], $item['description'])) {
                Permission::firstOrCreate(
                    ['name' => $item['name'], 'guard_name' => 'web'],
                    ['description' => $item['description']]
                );
            }

            // Se tiver subitens, percorre eles também
            if (isset($item['items']) && is_array($item['items'])) {
                $this->createPermissionsRecursive($item['items']);
            }
        }
    }
}
