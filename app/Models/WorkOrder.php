<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['property_id', 'lease_id', 'raised_by', 'assigned_to', 'title', 'description', 'priority', 'status', 'resolved_at'])]
class WorkOrder extends Model
{
    use HasFactory;

    /**
     * Belongs to a property
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Optionally belongs to a lease
     */
    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    /**
     * Raised by a user (tenant or property manager)
     */
    public function raisedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'raised_by');
    }

    /**
     * Optinally assigned to a user
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Has many updates
     */
    public function updates(): HasMany
    {
        return $this->hasMany(WorkOrderUpdate::class);
    }
}
