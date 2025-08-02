<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bakery;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BakerySeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::role('super-admin')->first();

        if (!$superAdmin) {
            throw new \Exception('Nenhum super-admin encontrado. Crie um antes de rodar este seeder.');
        }

        DB::transaction(function () use ($superAdmin) {
            // ========== Padaria Central ==========
            $email1 = 'carlos@padariacentral.com';
            Person::where('email', $email1)->delete();
            User::where('email', $email1)->delete();
            Bakery::where('slug', 'padaria-central')->delete();

            $person1 = Person::create([
                'name' => 'Carlos Central',
                'email' => $email1,
                'nickname' => 'Carlos',
                'nif' => '12.345.678/0001-01',
                'phone' => '(11) 99999-1111',
                'address' => 'Rua A, Centro',
                'reference' => 'Perto da praça',
                'number' => 123,
                'zip_code' => '12345-000',
                'city_id' => 3531,
            ]);

            $admin1 = $person1->user()->create([
                'email' => $email1,
                'password' => Hash::make('12345678'),
                'is_super_admin' => false,
            ]);

            $admin1->assignRole('admin');

            $bakery1 = Bakery::create([
                'name' => 'Padaria Central',
                'slug' => 'padaria-central',
                'nif' => '12.345.678/0001-01',
                'phone' => '(11) 99999-1111',
                'created_by' => $superAdmin->id,
                'admin_id' => $admin1->id,
                'trial_until' => now()->addDays(7),
            ]);

            // Associa admin à padaria via tabela pivô
            $admin1->bakeries()->attach($bakery1->id);

            // ========== Padaria do Bairro ==========
            $email2 = 'maria@padariabairro.com';
            Person::where('email', $email2)->delete();
            User::where('email', $email2)->delete();
            Bakery::where('slug', 'padaria-do-bairro')->delete();

            $person2 = Person::create([
                'name' => 'Maria Bairro',
                'email' => $email2,
                'nickname' => 'Maria',
                'nif' => '98.765.432/0001-02',
                'phone' => '(11) 98888-2222',
                'address' => 'Rua B, Bairro C',
                'reference' => 'Próximo ao mercado',
                'number' => 456,
                'zip_code' => '54321-000',
                'city_id' => 3531,
            ]);

            $admin2 = $person2->user()->create([
                'email' => $email2,
                'password' => Hash::make('12345678'),
                'is_super_admin' => false,
            ]);

            $admin2->assignRole('admin');

            $bakery2 = Bakery::create([
                'name' => 'Padaria do Bairro',
                'slug' => 'padaria-do-bairro',
                'nif' => '98.765.432/0001-02',
                'phone' => '(11) 98888-2222',
                'created_by' => $superAdmin->id,
                'admin_id' => $admin2->id,
                'trial_until' => now()->addDays(7),
            ]);

            // Associa admin à padaria via tabela pivô
            $admin2->bakeries()->attach($bakery2->id);
        });
    }
}
