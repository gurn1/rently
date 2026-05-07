<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'legal_name', 'preferred_name', 'phone', 'address', 'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship', 'profile_image'])]
class UserProfile extends Model
{
    /**
     * Belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
}
