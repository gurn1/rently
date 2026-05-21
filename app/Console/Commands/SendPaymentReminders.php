<?php
namespace App\Console\Commands;

use App\Models\Payment;
use App\Notifications\PaymentDueNotification;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    protected $signature   = 'payments:send-reminders';
    protected $description = 'Send payment due reminders 3 days before due date';

    public function handle(): void
    {
        $reminderDays = setting('payment_reminder_days', 3);

        $payments = Payment::where('status', 'pending')
            ->whereBetween('due_date', [now(), now()->addDays($reminderDays)])
            ->with(['tenant', 'lease.property'])
            ->get();

        foreach ($payments as $payment) {
            $payment->tenant->notify(new PaymentDueNotification($payment));
            $this->info('Reminder sent for payment ' . $payment->id);
        }

        $this->info('Payment reminders sent: ' . $payments->count());
    }
}