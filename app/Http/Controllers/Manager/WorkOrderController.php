<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workOrders = WorkOrder::whereHas('property', function ($query) {
            $query->where('properties.property_manager_id', auth()->id());
        })
        ->with(['property', 'raisedBy'])
        ->latest()
        ->paginate(20);

        return view('dashboard.manager.work-orders.index', compact('workOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $properties = Property::where('property_manager_id', auth()->id())->get();
        return view('dashboard.manager.work-orders.create', compact('properties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id'  => 'required|exists:properties,id',
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'priority'     => 'required|in:low,medium,high,urgent',
        ]);

        $validated['raised_by'] = auth()->id();
        $validated['status'] = 'open';

        WorkOrder::create($validated);

        return redirect()->route('manager.work-orders.index')
            ->with('success', 'Work order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder)
    {
        $this->authorize('view', $workOrder);
        $workOrder->load(['property', 'raisedBy', 'assignedTo', 'updates.user']);

        return view('dashboard.manager.work-orders.show', compact('workOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder)
    {
        $this->authorize('update', $workOrder);
        $properties = Property::where('property_manager_id', auth()->id())->get();
        $tenants = auth()->user()->tenants;

        return view('dashboard.manager.work-orders.edit', compact('workOrder', 'properties', 'tenants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrder $workOrder)
    {
        $this->authorize('update', $workOrder);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'required|in:low,medium,high,urgent',
            'status'      => 'required|in:open,in_progress,pending_review,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validated['status'] === 'resolved' && !$workOrder->resolved_at) {
            $validated['resolved_at'] = now();
        }

        $workOrder->update($validated);

        return redirect()->route('manager.work-orders.show', $workOrder)
            ->with('success', 'Work order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrder $workOrder)
    {
        $this->authorize('delete', $workOrder);
        $workOrder->delete();

        return redirect()->route('manager.work-orders.index')
            ->with('success', 'Work order deleted.');
    }
}