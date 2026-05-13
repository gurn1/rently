<?php
namespace App\Notifications;

use App\Models\WorkOrder;
use App\Models\WorkOrderUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WorkOrderCommentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public WorkOrderUpdate $update,
        public WorkOrder $workOrder
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'       => $this->update->user->first_name . ' added an update to work order "' . $this->workOrder->title . '".',
            'preview'       => substr($this->update->comment, 0, 100),
            'work_order_id' => $this->workOrder->id,
            'user_id'       => $this->update->user_id,
            'type'          => 'work_order_comment',
        ];
    }
}