<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;

class LeaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leases = Lease::whereHas('property', function ($query) {
            $query->where('properties.property_manager_id', auth()->id());
        })
        ->with(['property', 'tenant'])
        ->latest()
        ->paginate(20);

        return view('dashboard.manager.leases.index', compact('leases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $properties = Property::where('property_manager_id', auth()->id())
            ->where('availability_status', 'available')
            ->get();

        $tenants = auth()->user()->tenants;

        return view('dashboard.manager.leases.create', compact('properties', 'tenants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id'  => 'required|exists:properties,id',
            'tenant_id'    => 'required|exists:users,id',
            'rent_amount'  => 'required|numeric|min:0',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after:start_date',
            'status'       => 'required|in:pending,active,ended,terminated',
        ]);

        $lease = Lease::create($validated);

        // Update property status to occupied
        Property::where('id', $validated['property_id'])
            ->update(['availability_status' => 'occupied']);

        return redirect()->route('manager.leases.show', $lease)
            ->with('success', 'Lease created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lease $lease)
    {
        $this->authorize('view', $lease);
        $lease->load(['property', 'tenant', 'documents', 'workOrders']);

        return view('dashboard.manager.leases.show', compact('lease'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lease $lease)
    {
        $this->authorize('update', $lease);

        $properties = Property::where('property_manager_id', auth()->id())->get();
        $tenants = auth()->user()->tenants;

        return view('dashboard.manager.leases.edit', compact('lease', 'properties', 'tenants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lease $lease)
    {
        $this->authorize('update', $lease);

        $validated = $request->validate([
            'rent_amount'        => 'required|numeric|min:0',
            'start_date'         => 'required|date',
            'end_date'           => 'nullable|date|after:start_date',
            'status'             => 'required|in:pending,active,ended,terminated',
            'termination_notes'  => 'nullable|string',
        ]);

        if ($validated['status'] === 'terminated' && !$lease->terminated_at) {
            $validated['terminated_at'] = now();

            // Free up the property
            $lease->property()->update(['availability_status' => 'available']);
        }

        if (in_array($validated['status'], ['ended', 'terminated'])) {
            $lease->property()->update(['availability_status' => 'available']);
        }

        $lease->update($validated);

        return redirect()->route('manager.leases.show', $lease)
            ->with('success', 'Lease updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lease $lease)
    {
        $this->authorize('delete', $lease);
        $lease->delete();

        return redirect()->route('manager.leases.index')
            ->with('success', 'Lease deleted successfully.');
    }
}