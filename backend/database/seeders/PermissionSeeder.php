<?php

// database/seeders/PermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Permission as AppPermission;


class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->createPermissions(AppPermission::PERMISSIONS);
    }

    private function createPermissions(array $items)
    {
        foreach ($items as $item) {
            // Se tiver subitens, chama recursivamente
            if (isset($item['items'])) {
                $this->createPermissions($item['items']);
            }

            // Se for folha (permissão com name + description)
            if (isset($item['name']) && isset($item['description'])) {
                Permission::firstOrCreate(
                    ['name' => $item['name']],
                    ['guard_name' => 'web']
                );
            }
        }
    }
}
