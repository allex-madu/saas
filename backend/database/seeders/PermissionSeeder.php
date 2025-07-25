<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'roles' => ['view', 'create', 'edit', 'delete'],
            'users' => ['view', 'create', 'edit', 'delete'],
            'permissions' => ['view', 'create', 'edit', 'delete'],
            // adicione mais módulos conforme necessário
        ];

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "$module.$action",
                    'guard_name' => 'web',
                ]);
            }
        }

        $this->command->info('Permissões criadas com sucesso.');
    }
}
