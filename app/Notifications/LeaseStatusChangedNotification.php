<?php
namespace App\Notifications;

use App\Models\Lease;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaseStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Lease $lease,
        public string $oldStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'     => 'Your lease status for ' . $this->lease->property->title . ' has changed from ' . $this->oldStatus . ' to ' . $this->lease->status . '.',
            'lease_id'    => $this->lease->id,
            'old_status'  => $this->oldStatus,
            'new_status'  => $this->lease->status,
            'type'        => 'lease_status_changed',
        ];
    }
}