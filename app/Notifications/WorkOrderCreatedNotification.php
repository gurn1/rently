<?php

namespace App\Notifications;

use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WorkOrderCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public WorkOrder $workOrder) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'       => 'A new work order has been raised: ' . $this->workOrder->title,
            'work_order_id' => $this->workOrder->id,
            'property_id'   => $this->workOrder->property_id,
            'raised_by'     => $this->workOrder->raisedBy->first_name . ' ' . $this->workOrder->raisedBy->last_name,
            'priority'      => $this->workOrder->priority,
            'type'          => 'work_order_created',
        ];
    }
}