<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['first_name', 'last_name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * As a property manager - owns properties
     */
    public function properties(): HasMany
    {
        return $this->HasMany(Property::class, 'property_manager_id');
    }

    /**
     * As a tenant - has leases
     */
    public function leases(): HasMany
    {
        return $this->HasMany(Lease::class, 'tenant_id');
    }

    /**
     * As a tenant - assigned to a property manager
     */
    public function propertyManager(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'property_manager_tenant', 'tenant_id', 'property_manager_id');
    }

    /**
     * As a property manager - has assigned tenants
     */
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'property_manager_tenant', 'property_manager_id', 'tenant_id');
    }

    /**
     * Profile
     */
    public function profile(): HasOne 
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
