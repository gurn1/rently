<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Lease;

class LeaseController extends Controller
{
    public function index()
    {
        $leases = Lease::where('tenant_id', auth()->id())
            ->with(['property'])
            ->latest()
            ->paginate(20);

        return view('dashboard.tenant.leases.index', compact('leases'));
    }

    public function show(Lease $lease)
    {
        $this->authorize('view', $lease);
        $lease->load(['property', 'documents', 'workOrders']);

        return view('dashboard.tenant.leases.show', compact('lease'));
    }
}