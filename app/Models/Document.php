<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['uploaded_by', 'tenant_id', 'lease_id', 'property_id', 'title', 'path', 'document_type', 'requires_signature', 'is_signed', 'signed_at'])]
class Document extends Model
{
    use HasFactory;

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    /**
     * Uploaded by a propert manager
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Belongs to a tenant
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    /**
     * Optionally belongs to a lease
     */
    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    /**
     * Optionally belongs to a property
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

}
