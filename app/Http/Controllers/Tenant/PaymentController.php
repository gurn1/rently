<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Payment;
use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::where('tenant_id', auth()->id())
            ->with('lease.property')
            ->latest()
            ->paginate(20);

        $failedPayments = Payment::where('tenant_id', auth()->id())
            ->where('status', 'failed')
            ->count();

        return view('dashboard.tenant.payments.index', compact('payments', 'failedPayments'));
    }

    public function show(Payment $payment)
    {
        if ($payment->tenant_id !== auth()->id()) {
            abort(403);
        }

        $payment->load('lease.property');
        return view('dashboard.tenant.payments.show', compact('payment'));
    }

    public function checkout(Lease $lease)
    {
        // Ensure tenant owns this lease
        if ($lease->tenant_id !== auth()->id()) {
            abort(403);
        }

        $pendingPayment = Payment::where('lease_id', $lease->id)
            ->where('tenant_id', auth()->id())
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$pendingPayment) {
            return redirect()->route('tenant.payments.index')
                ->with('error', 'No pending payment found.');
        }

        // Create Stripe checkout session
        $checkoutSession = auth()->user()->checkout([
            [
                'price_data' => [
                    'currency'     => 'gbp',
                    'product_data' => [
                        'name' => 'Rent — ' . $lease->property->title,
                    ],
                    'unit_amount' => $pendingPayment->amount * 100, // Stripe uses pence
                ],
                'quantity' => 1,
            ],
        ], [
            'success_url' => route('tenant.payments.success', $pendingPayment),
            'cancel_url'  => route('tenant.payments.index'),
            'metadata'    => [
                'payment_id' => $pendingPayment->id,
                'lease_id'   => $lease->id,
                'tenant_id'  => auth()->id(),
            ],
        ]);

        return redirect($checkoutSession->url);
    }

    public function success(Payment $payment)
    {
        if ($payment->tenant_id !== auth()->id()) {
            abort(403);
        }

        return view('dashboard.tenant.payments.success', compact('payment'));
    }
}