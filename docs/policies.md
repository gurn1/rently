# Rently — Authorization Policies

> All policies live in `app/Policies/`.
> Auto-discovered by Laravel — no manual registration needed.
> Last updated: May 2026

---

## Overview

Policies control model-level access. Middleware controls route-level access. They work together:

- **Middleware** — blocks a role from accessing a route entirely
- **Policy** — controls what a user can do with a specific model instance

Policies are called in controllers via:
```php
$this->authorize('view', $property);
$this->authorize('update', $lease);
$this->authorize('delete', $document);
```

---

## PropertyPolicy
**File:** `app/Policies/PropertyPolicy.php`

| Method | Admin | Property Manager | Tenant | Guest |
|--------|-------|-----------------|--------|-------|
| `viewAny` | ✅ | ✅ | ✅ | ✅ |
| `view` | ✅ | ✅ own only | ✅ leased only | ✅ |
| `create` | ✅ | ✅ | ❌ | ❌ |
| `update` | ✅ | ✅ own only | ❌ | ❌ |
| `delete` | ✅ | ❌ | ❌ | ❌ |

> `viewAny` and `view` accept `?User` (nullable) to allow guest access to public listings.

---

## LeasePolicy
**File:** `app/Policies/LeasePolicy.php`

| Method | Admin | Property Manager | Tenant |
|--------|-------|-----------------|--------|
| `viewAny` | ✅ | ✅ | ✅ |
| `view` | ✅ | ✅ own properties | ✅ own leases |
| `create` | ✅ | ✅ | ❌ |
| `update` | ✅ | ✅ own properties | ❌ |
| `delete` | ✅ | ❌ | ❌ |

---

## DocumentPolicy
**File:** `app/Policies/DocumentPolicy.php`

| Method | Admin | Property Manager | Tenant |
|--------|-------|-----------------|--------|
| `viewAny` | ✅ | ✅ | ✅ |
| `view` | ✅ | ✅ uploaded by them | ✅ assigned to them |
| `create` | ✅ | ✅ | ❌ |
| `update` | ✅ | ✅ uploaded by them | ❌ |
| `delete` | ✅ | ✅ uploaded by them | ❌ |

---

## WorkOrderPolicy
**File:** `app/Policies/WorkOrderPolicy.php`

| Method | Admin | Property Manager | Tenant |
|--------|-------|-----------------|--------|
| `viewAny` | ✅ | ✅ | ✅ |
| `view` | ✅ | ✅ own properties | ✅ raised by them |
| `create` | ✅ | ✅ | ✅ |
| `update` | ✅ | ✅ own properties | ❌ |
| `delete` | ✅ | ❌ | ❌ |

---

## MessagePolicy
**File:** `app/Policies/MessagePolicy.php`

| Method | Admin | Property Manager | Tenant |
|--------|-------|-----------------|--------|
| `viewAny` | ✅ | ✅ | ✅ |
| `view` | ✅ | ✅ in conversation | ✅ in conversation |
| `create` | ✅ | ✅ | ✅ |
| `update` | ❌ | ❌ | ❌ |
| `delete` | ✅ | ❌ | ❌ |

> `update` always returns `false` — messages cannot be edited once sent.

---

## Notes

**Conversation ownership** is checked directly in `ConversationController` rather than via a policy, since the `MessagePolicy` operates on `Message` not `Conversation`:

```php
// Manager
if ($conversation->property_manager_id !== auth()->id()) {
    abort(403);
}

// Tenant
if ($conversation->tenant_id !== auth()->id()) {
    abort(403);
}
```

**Payment access** is checked inline in `Tenant\PaymentController` rather than via a policy:

```php
if ($payment->tenant_id !== auth()->id()) {
    abort(403);
}
```
