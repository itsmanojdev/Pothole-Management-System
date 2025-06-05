<?php

namespace Database\Seeders;

use App\Models\User;
use App\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Laravel\Prompts\password;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'MK Citizen',
            'email' => 'mkcitizen@gmail.com',
            'password' => 'Citizen@123'
        ]);

        User::factory()->admin()->create([
            'name' => 'MK Admin',
            'email' => 'mkadmin@gmail.com',
            'password' => 'Admin@123'
        ]);

        User::factory()->superAdmin()->create([
            'name' => 'MK Super Admin',
            'email' => 'mksuperadmin@gmail.com',
            'password' => 'Superadmin@123'
        ]);

        User::factory()->create([
            'name' => 'Manoj',
            'email' => 'manoj@gmail.com',
            'password' => 'manojscmr333@',
            'role' => UserRole::MK,
        ]);

        User::factory(10)->create();
        User::factory(5)->admin()->create();
        User::factory(3)->superAdmin()->create();
    }
}
