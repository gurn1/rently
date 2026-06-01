<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\Property;
use App\Models\User;
use App\Models\WorkOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $leases = Lease::whereHas('property', function ($query) {
            $query->where('properties.property_manager_id', auth()->id());
        })
            ->with(['property', 'tenant'])
            ->latest()
            ->take(10);

        $workOrders = WorkOrder::whereHas('property', function ($query) {
            $query->where('properties.property_manager_id', auth()->id());
        })
            ->whereIn('status', ['open', 'in_progress'])
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->take(10)
            ->get();

        return view('dashboard.manager.dashboard', compact(
            'leases',
            'workOrders'
        ));
    }
}
