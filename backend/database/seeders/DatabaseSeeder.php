<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* php artisan db:seed  */
        $this->call([
            StateSeeder::class,              
            CitySeeder::class,               
            RoleSeeder::class,               
            PermissionSeederTree::class,     
            AdminUserSeeder::class,          
            SuperAdminSeeder::class,         
            SuperUserPermissionsSeeder::class, 
            UserSeederAlex::class,           
            UserSeeder::class,               
            BakerySeeder::class,             
        ]);

    }
}
