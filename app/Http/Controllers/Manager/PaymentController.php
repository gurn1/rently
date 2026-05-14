<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentDueNotification;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::whereHas('lease.property', function ($query) {
            $query->where('properties.property_manager_id', auth()->id());
        })
        ->with(['lease.property', 'tenant'])
        ->latest()
        ->paginate(20);

        return view('dashboard.manager.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment->lease);
        $payment->load(['lease.property', 'tenant']);

        return view('dashboard.manager.payments.show', compact('payment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lease_id'       => 'required|exists:leases,id',
            'amount'         => 'required|numeric|min:0',
            'due_date'       => 'required|date',
            'payment_method' => 'required|in:stripe,manual',
            'notes'          => 'nullable|string',
        ]);

        $lease = Lease::findOrFail($validated['lease_id']);

        $payment = Payment::create([
            'lease_id'       => $lease->id,
            'tenant_id'      => $lease->tenant_id,
            'amount'         => $validated['amount'],
            'due_date'       => $validated['due_date'],
            'payment_method' => $validated['payment_method'],
            'notes'          => $validated['notes'] ?? null,
            'status'         => 'pending',
        ]);

        // Notify tenant
        $lease->tenant->notify(new PaymentDueNotification($payment));

        return redirect()->route('manager.payments.index')
            ->with('success', 'Payment request created and tenant notified.');
    }

    public function markPaid(Payment $payment)
    {
        $this->authorize('update', $payment->lease);

        $payment->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Payment marked as paid.');
    }

    public function create()
    {
        $leases = Lease::whereHas('property', function ($query) {
            $query->where('properties.property_manager_id', auth()->id());
        })
        ->where('status', 'active')
        ->with(['property', 'tenant'])
        ->get();

        return view('dashboard.manager.payments.create', compact('leases'));
    }
}