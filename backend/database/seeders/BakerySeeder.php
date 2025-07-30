<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bakery;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BakerySeeder extends Seeder
{
    public function run(): void
    {
        // Busca o primeiro usuário com papel super-admin
        $superAdmin = User::role('super-admin')->first();

        if (!$superAdmin) {
            throw new \Exception('Nenhum super-admin encontrado. Crie um antes de rodar este seeder.');
        }

        // Padaria 1
        $padaria1 = Bakery::create([
            'name' => 'Padaria Central',
            'slug' => 'padaria-central',
            'nif' => '12.345.678/0001-01',
            'phone' => '(11) 99999-1111',
            'created_by' => $superAdmin->id,
            'trial_until' => now()->addDays(7),
        ]);

        $person1 = Person::create([
            'name' => 'Carlos Central',
            'email' => 'carlos@padariacentral.com',
            'nickname' => 'Carlos',
            'nif' => '12.345.678/0001-01',
            'phone' => '(11) 99999-1111',
            'address' => 'Rua A, Centro',
            'reference' => 'Perto da praça',
            'number' => 123,
            'zip_code' => '12345-000',
            'city_id' => 1,
        ]);

        $admin1 = User::create([
            'person_id' => $person1->id,
            'email' => $person1->email,
            'password' => Hash::make('senha123'),
            'bakery_id' => $padaria1->id,
        ]);

        $admin1->assignRole('admin');

        // Padaria 2
        $padaria2 = Bakery::create([
            'name' => 'Padaria do Bairro',
            'slug' => 'padaria-do-bairro',
            'nif' => '98.765.432/0001-02',
            'phone' => '(11) 98888-2222',
            'created_by' => $superAdmin->id,
            'trial_until' => now()->addDays(7),
        ]);

        $person2 = Person::create([
            'name' => 'Maria Bairro',
            'email' => 'maria@padariabairro.com',
            'nickname' => 'Maria',
            'nif' => '98.765.432/0001-02',
            'phone' => '(11) 98888-2222',
            'address' => 'Rua B, Bairro C',
            'reference' => 'Próximo ao mercado',
            'number' => 456,
            'zip_code' => '54321-000',
            'city_id' => 1,
        ]);

        $admin2 = User::create([
            'person_id' => $person2->id,
            'email' => $person2->email,
            'password' => Hash::make('senha123'),
            'bakery_id' => $padaria2->id,
        ]);

        $admin2->assignRole('admin');
    }
}
