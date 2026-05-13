<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Notifications\WorkOrderCreatedNotification;
use App\Models\Lease;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workOrders = WorkOrder::where('raised_by', auth()->id())
            ->with(['property'])
            ->latest()
            ->paginate(20);

        return view('dashboard.tenant.work-orders.index', compact('workOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get properties the tenant is currently leasing
        $leases = Lease::where('tenant_id', auth()->id())
            ->where('status', 'active')
            ->with('property')
            ->get();

        return view('dashboard.tenant.work-orders.create', compact('leases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'required|in:low,medium,high,urgent',
        ]);

        $validated['raised_by'] = auth()->id();
        $validated['status'] = 'open';

        WorkOrder::create($validated);

        $workOrder->load(['raisedBy', 'property.propertyManager']);
        if ($workOrder->property->propertyManager) {
            $workOrder->property->propertyManager->notify(new WorkOrderCreatedNotification($workOrder));
        }

        return redirect()->route('tenant.work-orders.index')
            ->with('success', 'Work order submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder)
    {
        $this->authorize('view', $workOrder);
        $workOrder->load(['property', 'assignedTo', 'updates.user']);

        return view('dashboard.tenant.work-orders.show', compact('workOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return false;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return false;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return false;
    }
}