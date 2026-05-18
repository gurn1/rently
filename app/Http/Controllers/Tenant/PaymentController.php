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

    public function checkout(Payment $payment)
    {
        // Ensure tenant owns this payment
        if ($payment->tenant_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->status !== 'pending') {
            return redirect()->route('tenant.payments.index')
                ->with('error', 'This payment has already been processed.');
        }

        $lease = $payment->lease;

        $checkoutSession = auth()->user()->checkout([
            [
                'price_data' => [
                    'currency'     => 'gbp',
                    'product_data' => [
                        'name' => 'Rent — ' . $lease->property->title . ' (' . $payment->due_date->format('F Y') . ')',
                    ],
                    'unit_amount' => (int)($payment->amount * 100),
                ],
                'quantity' => 1,
            ],
        ], [
            'success_url' => route('tenant.payments.success', $payment),
            'cancel_url'  => route('tenant.payments.index'),
            'metadata'    => [
                'payment_id' => $payment->id,
                'lease_id'   => $lease->id,
                'tenant_id'  => auth()->id(),
            ],
        ]);

        // Store checkout session ID against this specific payment
        $payment->update([
            'stripe_payment_intent_id' => $checkoutSession->id,
        ]);

        return redirect($checkoutSession->url);
    }

    public function success(Request $request, Payment $payment)
    {
        if ($payment->tenant_id !== auth()->id()) {
            abort(403);
        }

        // If still pending, verify with Stripe directly
        if ($payment->status === 'pending' && $payment->stripe_payment_intent_id) {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));

            try {
                // Retrieve the checkout session
                $session = $stripe->checkout->sessions->retrieve(
                    $payment->stripe_payment_intent_id
                );

                if ($session->payment_status === 'paid') {
                    $payment->update([
                        'status'                   => 'paid',
                        'paid_at'                  => now(),
                        'stripe_payment_intent_id' => $session->payment_intent, // store actual payment intent ID
                    ]);

                    $tenant  = $payment->tenant;
                    $manager = $payment->lease->property->propertyManager;
                    $admins  = \App\Models\User::role('admin')->get();

                    $tenant->notify(new \App\Notifications\PaymentSuccessfulNotification($payment));

                    if ($manager) {
                        $manager->notify(new \App\Notifications\PaymentSuccessfulNotification($payment));
                    }

                    foreach ($admins as $admin) {
                        $admin->notify(new \App\Notifications\PaymentSuccessfulNotification($payment));
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Stripe verification failed: ' . $e->getMessage());
            }
        }

        $payment->refresh();
        return view('dashboard.tenant.payments.success', compact('payment'));
    }
}