<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

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
}