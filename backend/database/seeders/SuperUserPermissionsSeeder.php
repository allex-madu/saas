<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class SuperUserPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'alexsuper@gmail.com';

        $superAdmin = User::where('email', $email)->first();

        if (!$superAdmin) {
            $this->command->warn("SuperAdmin com e-mail {$email} não encontrado.");
            return;
        }

        $permissions = Permission::all();
        $superAdmin->syncPermissions($permissions);

        $this->command->info("Todas as permissões foram atribuídas a {$email}.");
    }
}
