<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use App\Models\Lease;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = User::where('email', 'tenant@rently.com')->first();
        $property = Property::where('slug', '2-bed-apartment-manchester')->first();

        Lease::create([
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'status' => 'active',
            'rent_amount' => 1200.00,
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
        ]);
    }
}
