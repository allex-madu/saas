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
        $this->call([
            //PermissionSeederTree::class,// permissões da arvore
            SuperUserPermissionsSeeder::class,// todas as permissoes para o superUsuario
        ]);
    }
}
