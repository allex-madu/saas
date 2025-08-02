<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeederAlex extends Seeder
{
    public function run(): void
    {
        // Garante que a role 'usuario' exista
        if (!Role::where('name', 'usuario')->exists()) {
            Role::create(['name' => 'usuario']);
        }

        // Criação da pessoa
        $person = Person::create([
            'name' => 'User',
            'email' => 'allex@gmail.com',
            'nickname' => 'Allex Dev',
            'nif' => '53.308.122/0002-22',
            'phone' => '(43) 99873-6040',
            'address' => 'Rua São Pedro, Vila Jursuariandir',
            'reference' => 'Perto da praça',
            'number' => 11,
            'zip_code' => '18460-009',
            'city_id' => 3531,
        ]);

        // Criação do usuário vinculado à person
        $user = $person->user()->create([
            'email' => 'alex@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        // Atribuição do papel
        $user->assignRole('usuario');
    }
}
