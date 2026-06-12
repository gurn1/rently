# Rently — Eloquent Models & Relationships

> All models live in `app/Models/`.
> Last updated: May 2026

---

## Overview

Each model maps to a database table. Laravel infers the table name from the class name (e.g. `WorkOrder` → `work_orders`).

Where a foreign key uses a non-standard column name (e.g. `tenant_id`, `raised_by`), the table is passed explicitly to `belongsTo()` to avoid incorrect inference.

All models use PHP attribute syntax for fillable:
```php
#[Fillable(['field_one', 'field_two'])]
```

---

## User
**File:** `app/Models/User.php`
**Traits:** `HasFactory`, `Notifiable`, `HasRoles` (Spatie), `Billable` (Cashier)

| Relationship | Type | Notes |
|---|---|---|
| `properties()` | hasMany Property | As property manager |
| `leases()` | hasMany Lease | As tenant |
| `payments()` | hasMany Payment | As tenant |
| `profile()` | hasOne UserProfile | |
| `tenants()` | belongsToMany User | Via `property_manager_tenant` pivot |
| `propertyManager()` | belongsToMany User | Via `property_manager_tenant` pivot |

### Helper method — `routePrefix()`
Returns the route prefix string for the user's role. Used to build dynamic route names.

```php
public function routePrefix(): string
{
    return match($this->getRoleNames()->first()) {
        'property_manager' => 'manager',
        'admin'            => 'admin',
        'tenant'           => 'tenant',
        default            => 'tenant',
    };
}
```

Usage:
```php
// In controllers
auth()->user()->routePrefix(); // 'manager'

// In Blade
route(auth()->user()->routePrefix() . '.dashboard')
```

> This is necessary because the `property_manager` role name doesn't match the `manager` route prefix.

---

## UserProfile
**File:** `app/Models/UserProfile.php`

| Relationship | Type |
|---|---|
| `user()` | belongsTo User |

---

## Property
**File:** `app/Models/Property.php`

| Relationship | Type | Notes |
|---|---|---|
| `propertyManager()` | belongsTo User | Via `property_manager_id` |
| `images()` | hasMany PropertyImage | |
| `leases()` | hasMany Lease | |
| `workOrders()` | hasMany WorkOrder | |
| `documents()` | hasMany Document | |
| `amenities()` | belongsToMany Amenity | Via `amenity_property` pivot |

```php
$property->propertyManager;  // manager who owns this property
$property->images;           // all images
$property->amenities;        // attached amenities
$property->leases;           // all leases
```

---

## PropertyImage
**File:** `app/Models/PropertyImage.php`

| Relationship | Type |
|---|---|
| `property()` | belongsTo Property |

> Always use `Storage::url($image->path)` to generate image URLs — never use `$image->path` directly as a src.

---

## Amenity
**File:** `app/Models/Amenity.php`

| Relationship | Type |
|---|---|
| `properties()` | belongsToMany Property |

---

## Lease
**File:** `app/Models/Lease.php`

| Relationship | Type | Notes |
|---|---|---|
| `property()` | belongsTo Property | |
| `tenant()` | belongsTo User | Via `tenant_id` |
| `documents()` | hasMany Document | |
| `workOrders()` | hasMany WorkOrder | |
| `payments()` | hasMany Payment | |

---

## Document
**File:** `app/Models/Document.php`

| Relationship | Type | Notes |
|---|---|---|
| `uploadedBy()` | belongsTo User | Via `uploaded_by` |
| `tenant()` | belongsTo User | Via `tenant_id` |
| `lease()` | belongsTo Lease | Nullable |
| `property()` | belongsTo Property | Nullable |

---

## WorkOrder
**File:** `app/Models/WorkOrder.php`

| Relationship | Type | Notes |
|---|---|---|
| `property()` | belongsTo Property | |
| `lease()` | belongsTo Lease | Nullable |
| `raisedBy()` | belongsTo User | Via `raised_by` |
| `assignedTo()` | belongsTo User | Via `assigned_to`, nullable |
| `updates()` | hasMany WorkOrderUpdate | |

---

## WorkOrderUpdate
**File:** `app/Models/WorkOrderUpdate.php`

| Relationship | Type |
|---|---|
| `workOrder()` | belongsTo WorkOrder |
| `user()` | belongsTo User |

---

## Conversation
**File:** `app/Models/Conversation.php`

**Casts:** `last_message_at` → `datetime`

| Relationship | Type | Notes |
|---|---|---|
| `tenant()` | belongsTo User | Via `tenant_id` |
| `propertyManager()` | belongsTo User | Via `property_manager_id` |
| `messages()` | hasMany Message | |
| `latestMessage()` | hasMany Message | Latest, limit 1. For inbox previews. |

---

## Message
**File:** `app/Models/Message.php`

**Casts:** `read_at` → `datetime`

| Relationship | Type | Notes |
|---|---|---|
| `conversation()` | belongsTo Conversation | |
| `sender()` | belongsTo User | Via `sender_id` |

---

## Payment
**File:** `app/Models/Payment.php`

**Casts:** `due_date` → `datetime`, `paid_at` → `datetime`

| Relationship | Type | Notes |
|---|---|---|
| `lease()` | belongsTo Lease | |
| `tenant()` | belongsTo User | Via `tenant_id` |
