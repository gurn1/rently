<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentFailedNotification;
use App\Notifications\PaymentSuccessfulNotification;
use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class WebhookController extends CashierWebhookController
{
    public function handleCheckoutSessionCompleted(array $payload)
    {
        $metadata = $payload['data']['object']['metadata'] ?? [];

        if (isset($metadata['payment_id'])) {
            $payment = Payment::find($metadata['payment_id']);

            if ($payment) {
                $payment->update([
                    'status'                   => 'paid',
                    'paid_at'                  => now(),
                    'stripe_payment_intent_id' => $payload['data']['object']['payment_intent'] ?? null,
                ]);

                $tenant = $payment->tenant;
                $manager = $payment->lease->property->propertyManager;
                $admins = User::role('admin')->get();

                // Notify tenant
                $tenant->notify(new PaymentSuccessfulNotification($payment));

                // Notify manager
                if ($manager) {
                    $manager->notify(new PaymentSuccessfulNotification($payment));
                }

                // Notify admins
                foreach ($admins as $admin) {
                    $admin->notify(new PaymentSuccessfulNotification($payment));
                }
            }
        }
    }

    public function handlePaymentIntentPaymentFailed(array $payload)
    {
        $paymentIntentId = $payload['data']['object']['id'];

        $payment = Payment::where('stripe_payment_intent_id', $paymentIntentId)->first();

        if ($payment) {
            $payment->update(['status' => 'failed']);

            $tenant = $payment->tenant;
            $manager = $payment->lease->property->propertyManager;
            $admins = User::role('admin')->get();

            // Notify all parties
            $tenant->notify(new PaymentFailedNotification($payment));

            if ($manager) {
                $manager->notify(new PaymentFailedNotification($payment));
            }

            foreach ($admins as $admin) {
                $admin->notify(new PaymentFailedNotification($payment));
            }
        }
    }
}