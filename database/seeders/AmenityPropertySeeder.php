<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmenityPropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apartment = Property::where('slug', '2-bed-apartment-manchester')->first();
        $house = Property::where('slug', '3-bed-house-leeds')->first();

        $apartment->amenities()->attach([
            Amenity::where('slug', 'parking')->first()->id,
            Amenity::where('slug', 'broadband-ready')->first()->id,
            Amenity::where('slug', 'washing-machine')->first()->id,
            Amenity::where('slug', 'furnished')->first()->id,
        ]);

        $house->amenities()->attach([
            Amenity::where('slug', 'parking')->first()->id,
            Amenity::where('slug', 'garden')->first()->id,
            Amenity::where('slug', 'garage')->first()->id,
            Amenity::where('slug', 'central-heating')->first()->id,
            Amenity::where('slug', 'double-glazing')->first()->id,
            Amenity::where('slug', 'pet-friendly')->first()->id,
        ]);
    }
}
