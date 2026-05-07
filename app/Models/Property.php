<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PropertyImage;

#[Fillable(['property_manager_id','title','slug','description','key_features','address','latitude','longitude','price','property_type','bedrooms','bathrooms','size','availability_status'])]
class Property extends Model
{
    use HasFactory;

    /**
     * Belongs to a property manager
     */
    public function propertyManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'property_manager_id');
    }

    /**
     * Has many images
     */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    /**
     * Has many leases
     */
    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }

    /**
     * Has many work orders
     */
    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    /**
     * Belongs to many anenities
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }

}
