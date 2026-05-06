<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@rently.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        $manager = User::create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'manager@rently.com',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole('property_manager');

        $tenant = User::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'tenant@rently.com',
            'password' => Hash::make('password'),
        ]);
        $tenant->assignRole('tenant');
    }
}
