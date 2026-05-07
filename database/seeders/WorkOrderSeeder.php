<?php

namespace Database\Seeders;

use App\Models\WorkOrder;
use App\Models\WorkOrderUpdate;
use App\Models\Lease;
use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::where('email', 'manager@rently.com')->first();
        $tenant = User::where('email', 'tenant@rently.com')->first();
        $property = Property::where('slug', '2-bed-apartment-manchester')->first();
        $lease = Lease::first();

        $workOrder = WorkOrder::create([
            'property_id' => $property->id,
            'lease_id' => $lease->id,
            'raised_by' => $tenant->id,
            'assigned_to' => $manager->id,
            'title' => 'Boiler not heating water',
            'description' => 'The boiler is running but not producing hot water. Issue started two days ago.',
            'priority' => 'high',
            'status' => 'in_progress',
        ]);

        WorkOrderUpdate::create([
            'work_order_id' => $workOrder->id,
            'user_id' => $manager->id,
            'comment' => 'Contacted the plumber, they will visit on the 15th between 9am and 12pm.',
        ]);

        WorkOrderUpdate::create([
            'work_order_id' => $workOrder->id,
            'user_id' => $tenant->id,
            'comment' => 'Thanks for letting me know, I will make sure to be home.',
        ]);
    }
}
