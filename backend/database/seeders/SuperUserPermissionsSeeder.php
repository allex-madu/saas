<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class SuperUserPermissionsSeeder extends Seeder
{
    /**
     * Executa o seeder e atribui todas as permissões existentes ao super usuário.
     */
    public function run(): void
    {
        // Altere este valor se seu super usuário for identificado por outro campo
        $superUser = User::where('email', 'alexsuper@gmail.com')->first();

        if (!$superUser) {
            $this->command->warn('Usuário alexsuper@gmail.com não encontrado.');
            return;
        }

        // Atribui todas as permissões disponíveis no sistema
        $permissions = Permission::all();
        $superUser->syncPermissions($permissions);

        $this->command->info('Todas as permissões foram atribuídas ao super usuário.');
    }
}
