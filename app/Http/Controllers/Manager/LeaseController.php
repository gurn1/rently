<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Notifications\LeaseStatusChangedNotification;
use App\Models\Lease;
use App\Models\Property;
use App\Models\User;
use App\Services\LeasePaymentService;
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
        $propertyOptions = $this->getPropertyOptions();

        return view('dashboard.manager.leases.create', compact('properties', 'tenants', 'propertyOptions'));
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

        // Auto-generate payments if lease is active
        if ($lease->status === 'active') {
            $paymentMethod = $request->input('payment_method', setting('default_payment_method', 'stripe'));
            app(LeasePaymentService::class)->generatePayments($lease, $paymentMethod);
        }

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
        $propertyOptions = $this->getPropertyOptions();

        return view('dashboard.manager.leases.edit', compact('lease', 'properties', 'tenants', 'propertyOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lease $lease)
    {
        $this->authorize('update', $lease);

        $oldStatus = $lease->status; // capture before update

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

        if ($lease->wasChanged('status')) {
            $lease->tenant->notify(new LeaseStatusChangedNotification($lease, $oldStatus));
        }

        // Cancel future payments if lease ended or terminated
        if (in_array($validated['status'], ['ended', 'terminated'])) {
            app(LeasePaymentService::class)->cancelFuturePayments($lease);
        }

        // Generate payments if lease just became active
        if ($oldStatus !== 'active' && $validated['status'] === 'active') {
            $paymentMethod = $lease->payments()->latest()->first()?->payment_method ?? 'stripe';
            app(LeasePaymentService::class)->generatePayments($lease, $paymentMethod);
        }

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

    /**
     * Helper to obtain a list of properties
     */
    private function getPropertyOptions(): array
    {
        return Property::with('propertyManager')->get()->mapWithKeys(function ($property) {
            $label = $property->title;
            if ($property->propertyManager) {
                $label .= " ({$property->propertyManager->first_name} {$property->propertyManager->last_name})";
            }
            return [$property->id => $label];
        })->toArray();
    }
}