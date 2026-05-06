<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Amenity;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            'Parking', 'Garden', 'Garage', 'Central Heating',
            'Double Glazing', 'Broadband Ready', 'Washing Machine',
            'Dishwasher', 'Furnished', 'Pet Friendly',
        ];

        foreach ($amenities as $name) {
            Amenity::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
