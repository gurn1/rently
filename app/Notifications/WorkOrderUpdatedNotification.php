<?php
namespace App\Notifications;

use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WorkOrderUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public WorkOrder $workOrder,
        public string $oldStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'       => 'Work order "' . $this->workOrder->title . '" status changed from ' . $this->oldStatus . ' to ' . $this->workOrder->status . '.',
            'work_order_id' => $this->workOrder->id,
            'old_status'    => $this->oldStatus,
            'new_status'    => $this->workOrder->status,
            'type'          => 'work_order_updated',
        ];
    }
}