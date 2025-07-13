<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Permissões básicas
        $permissions = [
            'view_products',
            'create_products',
            'update_products',
            'delete_products',
            'view_sales',
            'create_sales',
            'view_dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Papéis (roles)
        $admin = Role::firstOrCreate(['name' => 'super-admin']);
        $manager = Role::firstOrCreate(['name' => 'gerente']);
        $cashier = Role::firstOrCreate(['name' => 'caixa']);

        // Super-admin recebe todas as permissões
        $admin->syncPermissions(Permission::all());

        // Gerente recebe permissões limitadas
        $manager->syncPermissions([
            'view_products',
            'create_products',
            'update_products',
            'view_sales',
            'view_dashboard',
        ]);

        // Caixa recebe permissões mínimas
        $cashier->syncPermissions([
            'create_sales',
            'view_dashboard',
        ]);

        // Atribui papéis para usuários existentes (opcional)
        if ($user = User::where('email', 'admin@padaria.com')->first()) {
            $user->assignRole('super-admin');
        }

        if ($user = User::where('email', 'gerente@padaria.com')->first()) {
            $user->assignRole('gerente');
        }

        if ($user = User::where('email', 'caixa@padaria.com')->first()) {
            $user->assignRole('caixa');
        }
    }
}
