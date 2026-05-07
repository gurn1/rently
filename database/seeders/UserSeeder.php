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
        $admin->profile()->create([
            'legal_name' => 'Admin User',
            'preferred_name' => 'Admin',
            'phone' => '07700 900100',
            'address' => '1 Admin Street, London, EC1A 1BB',
            'emergency_contact_name' => 'Admin Contact',
            'emergency_contact_phone' => '07700 900101',
            'emergency_contact_relationship' => 'Spouse',
        ]);

        $manager = User::create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'manager@rently.com',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole('property_manager');
        $manager->profile()->create([
            'legal_name' => 'John Smith',
            'preferred_name' => 'John',
            'phone' => '07700 900123',
            'address' => '15 Example Street, Manchester, M1 2AB',
            'emergency_contact_name' => 'Jane Smith',
            'emergency_contact_phone' => '07700 900456',
            'emergency_contact_relationship' => 'Spouse',
        ]);

        $tenant = User::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'tenant@rently.com',
            'password' => Hash::make('password'),
        ]);
        $tenant->assignRole('tenant');
        $tenant->profile()->create([
            'legal_name' => 'Jane Doe',
            'preferred_name' => 'Jane',
            'phone' => '07700 900789',
            'address' => '12 Piccadilly, Manchester, M1 1AA',
            'emergency_contact_name' => 'Bob Doe',
            'emergency_contact_phone' => '07700 900999',
            'emergency_contact_relationship' => 'Parent',
        ]);

        // Assign tenant to property manager
        $manager->tenants()->attach($tenant->id);
    }
}
