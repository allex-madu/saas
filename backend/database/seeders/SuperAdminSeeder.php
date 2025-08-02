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
        $email = 'alexsuper@gmail.com';

        // Remove registros anteriores com o mesmo e-mail
        User::where('email', $email)->delete();
        Person::where('email', $email)->delete();

        // Gera um NIF aleatório no formato básico de CNPJ
        $nif = sprintf('%02d.%03d.%03d/%04d-%02d', 
            rand(10, 99), rand(100, 999), rand(100, 999),
            rand(1000, 9999), rand(10, 99)
        );

        // Criação da pessoa vinculada ao super admin
        $person = Person::create([
            'name' => 'Super',
            'email' => $email,
            'nickname' => 'Alex Super',
            'nif' => $nif,
            'phone' => '(43) 99873-6040',
            'address' => 'Rua São Pedro, Vila Jurandir',
            'reference' => 'Perto da praça',
            'number' => 43,
            'zip_code' => '18460-009',
            'city_id' => '3531'
        ]);

        // Criação do usuário super admin
        $user = User::create([
            'person_id' => $person->id,
            'email' => $email,
            'password' => Hash::make('12345678'),
            'is_super_admin' => true,
        ]);

        // Atribui papel
        $user->assignRole('super-admin');
    }
}
