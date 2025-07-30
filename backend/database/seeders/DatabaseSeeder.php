<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* php artisan db:seed  */
        $this->call([
            StateSeeder::class,              // Criar estados
            CitySeeder::class,               // Criar cidades
            RoleSeeder::class,               // Criar roles (papéis)
            PermissionSeederTree::class,     // Criar árvore de permissões
            AdminUserSeeder::class,          // Criar usuário admin
            SuperAdminSeeder::class,         // Criar super admin
            SuperUserPermissionsSeeder::class, // Atribuir permissões ao super admin
            UserSeederAlex::class,           // Criar usuário específico (Alex)
            UserSeeder::class,               // Criar outros usuários
            BakerySeeder::class,             // Criar padarias
        ]);

    }
}
