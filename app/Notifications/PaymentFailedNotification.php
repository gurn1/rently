<?php
namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification
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
            'message'    => 'Payment of £' . number_format($this->payment->amount, 2) . ' failed. Please update your payment details.',
            'payment_id' => $this->payment->id,
            'lease_id'   => $this->payment->lease_id,
            'amount'     => $this->payment->amount,
            'type'       => 'payment_failed',
        ];
    }
}