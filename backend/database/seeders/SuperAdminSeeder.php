<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Person;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Gera um NIF aleatório no formato básico de CNPJ: 00.000.000/0000-00
        $nif = sprintf('%02d.%03d.%03d/%04d-%02d', 
            rand(10, 99), rand(100, 999), rand(100, 999),
            rand(1000, 9999), rand(10, 99)
        );

        $person = Person::create([
            'name' => 'Super',
            'email' => 'alexsuper@gmail.com',
            'nickname' => 'Alex Super',
            'nif' => $nif,
            'phone' => '(43) 99873-6040',
            'address' => 'Rua São Pedro, Vila Jurandir',
            'reference' => 'Perto da praça',
            'number' => 43,
            'zip_code' => '18460-009',
            'city_id' => '3531'
        ]);

        $user = User::create([
            'person_id' => $person->id,
            'email' => 'alexsuper@gmail.com',
            'password' => Hash::make('12345678'),
            'is_super_admin' => true,
        ]);

        $user->assignRole('super-admin');
    }
}
