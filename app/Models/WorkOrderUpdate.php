<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['work_order_id', 'user_id', 'comment'])]
class WorkOrderUpdate extends Model
{
    use HasFactory;

    /**
     * Belongs to a work order
     */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Written by a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
