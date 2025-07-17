<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
       $person = Person::create([
            'name' => 'Allex',
            'email' => 'allex@gmail.com',
            'nickname' => 'Allex Dev',
            'nif' => '11.901.522/0001-21',
            'phone' => '(43) 99873-6040',
            'address' => 'Rua São Pedro, Vila Jurandir',
            'reference' => 'Perto da praça',
            'number' => 28,
            'zip_code' => '18460-009',
            'city_id' => '3531'
        ]);

        $user = User::create([
            'person_id' => $person->id,
            'email' => 'alexadmin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('admin');
    }
}
