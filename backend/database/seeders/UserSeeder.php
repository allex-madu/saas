<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Garante que a role 'usuario' exista (evita erro na atribuição)
        if (!\Spatie\Permission\Models\Role::where('name', 'usuario')->exists()) {
            \Spatie\Permission\Models\Role::create(['name' => 'usuario']);
        }

        for ($i = 1; $i <= 20; $i++) {
            $person = Person::factory()->create();

            $user = $person->user()->create([
                'email' => $person->email,
                'password' => Hash::make('12345678'),
            ]);

            $user->assignRole('usuario');
        }
    }
}
