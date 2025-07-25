<?php


namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

    class PermissionSeederTree extends Seeder
    {
    public function run(): void
    {
        $structure = Permission::treeStructure();
        $this->createPermissions($structure);
    }

    private function createPermissions(array $structure): void
    {
        foreach ($structure as $group) {
            if (!isset($group['items'])) continue;

            foreach ($group['items'] as $module) {
                if (!isset($module['items'])) continue;

                foreach ($module['items'] as $permission) {
                    Permission::firstOrCreate(
                        ['name' => $permission['name'], 'guard_name' => 'web'],
                        ['description' => $permission['description']]
                    );
                }
            }
        }
    }
}
