<?php
namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentDueNotification extends Notification
{
    use Queueable;

    public function __construct(public Payment $payment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'    => 'Rent payment of £' . number_format($this->payment->amount, 2) . ' is due on ' . $this->payment->due_date->format('d/m/Y') . '.',
            'payment_id' => $this->payment->id,
            'lease_id'   => $this->payment->lease_id,
            'amount'     => $this->payment->amount,
            'due_date'   => $this->payment->due_date,
            'type'       => 'payment_due',
        ];
    }
}