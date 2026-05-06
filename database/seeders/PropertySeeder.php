<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::where('email', 'manager@rently.com')->first();

        Property::create([
            'property_manager_id' => $manager->id,
            'title' => '2 Bed Apartment in Manchester',
            'slug' => '2-bed-apartment-manchester',
            'description' => 'A modern 2 bedroom apartment in the heart of Manchester city centre.',
            'key_features' => 'Recently refurbished. Close to transport links. South facing.',
            'address' => '12 Piccadilly, Manchester, M1 1AA',
            'latitude' => 53.4808,
            'longitude' => -2.2426,
            'price' => 1200.00,
            'property_type' => 'apartment',
            'bedrooms' => 2,
            'bathrooms' => 1,
            'size' => 750,
            'availability_status' => 'available',
        ]);

        Property::create([
            'property_manager_id' => $manager->id,
            'title' => '3 Bed House in Leeds',
            'slug' => '3-bed-house-leeds',
            'description' => 'A spacious 3 bedroom semi-detached house in a quiet residential area.',
            'key_features' => 'Large garden. Off street parking. Near good schools.',
            'address' => '45 Headingley Lane, Leeds, LS6 1AA',
            'latitude' => 53.8208,
            'longitude' => -1.5555,
            'price' => 1500.00,
            'property_type' => 'house',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'size' => 1100,
            'availability_status' => 'occupied',
        ]);
    }
}
