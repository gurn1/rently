<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['tenant_id', 'property_manager_id', 'last_message_at'])]
class Conversation extends Model
{
    use HasFactory;

    /**
     * Belongs to a tenant
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    /**
     * Belongs to a property manager
     */
    public function propertyManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'property_manager_id');
    }

    /**
     * Has many messages
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Lastest message helper - useful for inbox previews
     */
    public function latestMessage(): HasMany
    {
        return $this->hasMany(Message::class)->latest()->limit(1);
    }

    /**
     * Add casts
     */
    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

}
