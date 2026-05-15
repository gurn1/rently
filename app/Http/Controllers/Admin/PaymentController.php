<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Payment;
use App\Notifications\PaymentDueNotification;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['lease.property', 'tenant'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_paid'    => Payment::where('status', 'paid')->sum('amount'),
            'total_pending' => Payment::where('status', 'pending')->sum('amount'),
            'total_failed'  => Payment::where('status', 'failed')->count(),
        ];

        return view('dashboard.admin.payments.index', compact('payments', 'stats'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['lease.property', 'tenant']);
        return view('dashboard.admin.payments.show', compact('payment'));
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

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment request created and tenant notified.');
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,failed,refunded',
            'notes'  => 'nullable|string',
        ]);

        $oldStatus = $payment->status;

        $payment->update([
            'status'  => $validated['status'],
            'paid_at' => $validated['status'] === 'paid' ? now() : $payment->paid_at,
            'notes'   => $validated['notes'] ?? $payment->notes,
        ]);

        // Notify tenant if status changed
        if ($oldStatus !== $validated['status']) {
            if ($validated['status'] === 'paid') {
                $payment->tenant->notify(new \App\Notifications\PaymentSuccessfulNotification($payment));
            } elseif ($validated['status'] === 'failed') {
                $payment->tenant->notify(new \App\Notifications\PaymentFailedNotification($payment));
            }
        }

        return redirect()->back()->with('success', 'Payment status updated.');
    }

    public function create()
    {
        $leases = Lease::with(['property', 'tenant'])
        ->get();

        return view('dashboard.admin.payments.create', compact('leases'));
    }

}