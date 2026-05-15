<?php

namespace App\Http\Controllers\Admin;

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
        $leases = Lease::with(['property', 'tenant'])
            ->latest()
            ->paginate(20);

        return view('dashboard.admin.leases.index', compact('leases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $properties = Property::with('propertyManager')->get();
        $tenants = User::role('tenant')->get();

        return view('dashboard.admin.leases.create', compact('properties', 'tenants'));
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

        Property::where('id', $validated['property_id'])
            ->update(['availability_status' => 'occupied']);

        return redirect()->route('admin.leases.show', $lease)
            ->with('success', 'Lease created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lease $lease)
    {
        $this->authorize('view', $lease);
        $lease->load(['property', 'tenant', 'documents', 'workOrders']);

        return view('dashboard.admin.leases.show', compact('lease'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lease $lease)
    {
        $properties = Property::all();
        $tenants = User::role('tenant')->get();

        return view('dashboard.admin.leases.edit', compact('lease', 'properties', 'tenants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lease $lease)
    {
        $validated = $request->validate([
            'rent_amount'       => 'required|numeric|min:0',
            'start_date'        => 'required|date',
            'end_date'          => 'nullable|date|after:start_date',
            'status'            => 'required|in:pending,active,ended,terminated',
            'termination_notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'terminated' && !$lease->terminated_at) {
            $validated['terminated_at'] = now();
            $lease->property()->update(['availability_status' => 'available']);
        }

        if (in_array($validated['status'], ['ended', 'terminated'])) {
            $lease->property()->update(['availability_status' => 'available']);
        }

        $lease->update($validated);

        return redirect()->route('admin.leases.show', $lease)
            ->with('success', 'Lease updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lease $lease)
    {
        $lease->delete();

        return redirect()->route('admin.leases.index')
            ->with('success', 'Lease deleted successfully.');
    }
}