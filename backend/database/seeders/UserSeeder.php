<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Criar 20 usuários
        for ($i = 0; $i < 20; $i++) {
            $person = Person::factory()->create();

            $user = User::create([
                'person_id' => $person->id,
                'email' => $person->email, // ou diferente se quiser
                'password' => Hash::make('12345678'), // senha padrão
            ]);

            $user->assignRole('usuario');
        }
    }
}
