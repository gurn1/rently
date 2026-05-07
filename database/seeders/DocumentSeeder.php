<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Lease;
use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::where('email', 'manager@rently.com')->first();
        $tenant = User::where('email', 'tenant@rently.com')->first();
        $lease = Lease::first();
        $property = Property::where('slug', '2-bed-apartment-manchester')->first();

        Document::create([
            'uploaded_by' => $manager->id,
            'tenant_id' => $tenant->id,
            'lease_id' => $lease->id,
            'property_id' => $property->id,
            'title' => 'Tenancy Agreement 2026',
            'path' => 'documents/tenancy-agreement-2026.pdf',
            'document_type' => 'tenancy_agreement',
            'requires_signature' => true,
            'is_signed' => false,
        ]);

        Document::create([
            'uploaded_by' => $manager->id,
            'tenant_id' => $tenant->id,
            'lease_id' => null,
            'property_id' => $property->id,
            'title' => 'Property Welcome Pack',
            'path' => 'documents/welcome-pack.pdf',
            'document_type' => 'welcome_pack',
            'requires_signature' => false,
            'is_signed' => false,
        ]);
    }
}
