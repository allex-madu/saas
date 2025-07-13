<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\User;


class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $person = Person::create([
            'name' => 'Allex',
            'email' => 'alexsuper@gmail.com',
            'nickname' => 'Alex Super',
            'nif' => '40.908.122/0001-21',
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
