<?php
namespace App\Services;

use App\Models\Lease;
use App\Models\Payment;
use App\Notifications\PaymentDueNotification;
use Carbon\Carbon;

class LeasePaymentService
{
    public function generatePayments(Lease $lease, string $paymentMethod = 'stripe'): void
    {
        // Respect the auto_generate_payments setting
        if (!setting('auto_generate_payments', true)) {
            return;
        }

        $startDate = Carbon::parse($lease->start_date);
        $endDate   = $lease->end_date
            ? Carbon::parse($lease->end_date)
            : Carbon::parse($lease->start_date)->addMonths(12);

        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            // Avoid duplicates
            $exists = Payment::where('lease_id', $lease->id)
                ->whereYear('due_date', $current->year)
                ->whereMonth('due_date', $current->month)
                ->exists();

            if (!$exists) {
                Payment::create([
                    'lease_id'       => $lease->id,
                    'tenant_id'      => $lease->tenant_id,
                    'amount'         => $lease->rent_amount,
                    'currency'       => 'gbp',
                    'status'         => 'pending',
                    'payment_method' => $paymentMethod,
                    'due_date'       => $current->copy()->startOfMonth(),
                ]);
            }

            $current->addMonth();
        }
    }

    public function extendPayments(Lease $lease, int $months = 3): void
    {
        $lastPayment = Payment::where('lease_id', $lease->id)
            ->latest('due_date')
            ->first();

        $startFrom = $lastPayment
            ? Carbon::parse($lastPayment->due_date)->addMonth()
            : Carbon::now()->startOfMonth();

        $endAt = $startFrom->copy()->addMonths($months);
        $current = $startFrom->copy();

        while ($current->lte($endAt)) {
            $exists = Payment::where('lease_id', $lease->id)
                ->whereYear('due_date', $current->year)
                ->whereMonth('due_date', $current->month)
                ->exists();

            if (!$exists) {
                Payment::create([
                    'lease_id'       => $lease->id,
                    'tenant_id'      => $lease->tenant_id,
                    'amount'         => $lease->rent_amount,
                    'currency'       => 'gbp',
                    'status'         => 'pending',
                    'payment_method' => $lease->payments()->latest()->first()?->payment_method ?? 'stripe',
                    'due_date'       => $current->copy()->startOfMonth(),
                ]);
            }

            $current->addMonth();
        }
    }

    public function cancelFuturePayments(Lease $lease): void
    {
        Payment::where('lease_id', $lease->id)
            ->where('status', 'pending')
            ->where('due_date', '>', now())
            ->delete();
    }
}