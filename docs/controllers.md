# Rently — Controllers

> All controllers live in `app/Http/Controllers/`.
> Last updated: May 2026

---

## Structure

Controllers are namespaced by role to keep concerns separated. Each role has its own subdirectory.

```
app/Http/Controllers/
├── PropertyController.php              ← public facing
├── MessageController.php               ← shared
├── WorkOrderUpdateController.php       ← shared
├── PropertyImageController.php         ← shared
├── UserProfileController.php           ← shared (all roles)
├── NotificationController.php          ← shared (all roles)
├── WebhookController.php               ← Stripe webhooks
├── Manager/
│   ├── PropertyController.php
│   ├── LeaseController.php
│   ├── DocumentController.php
│   ├── WorkOrderController.php
│   ├── ConversationController.php
│   └── PaymentController.php
├── Tenant/
│   ├── LeaseController.php
│   ├── DocumentController.php
│   ├── WorkOrderController.php
│   ├── ConversationController.php
│   └── PaymentController.php
└── Admin/
    ├── PropertyController.php
    ├── LeaseController.php
    ├── DocumentController.php
    ├── WorkOrderController.php
    ├── PaymentController.php
    └── UserController.php
```

---

## Public Controllers

### PropertyController
**Purpose:** Public property listings and single property view.

| Method | Route | Notes |
|--------|-------|-------|
| `index()` | GET /properties | Paginated listings with images and amenities eager loaded |
| `show(Property)` | GET /properties/{slug} | Single property with images, amenities, propertyManager |

---

## Shared Controllers

### MessageController
**Purpose:** Send messages. Used by both tenant and manager routes.

| Method | Notes |
|--------|-------|
| `store(Request, Conversation)` | Validates ownership, creates message, updates `last_message_at`, fires `NewMessageNotification` |

### WorkOrderUpdateController
**Purpose:** Add updates/comments to work orders. Used by all roles.

| Method | Notes |
|--------|-------|
| `store(Request, WorkOrder)` | Checks user belongs to work order, creates update, fires `WorkOrderCommentNotification` |

### PropertyImageController
**Purpose:** Manage property images. Used by manager and admin routes.

| Method | Notes |
|--------|-------|
| `store(Request, Property)` | Uploads multiple images, sets first as featured if none exist |
| `destroy(Property, PropertyImage)` | Deletes image from storage and DB, promotes next image to featured if deleted was featured |
| `setFeatured(Property, PropertyImage)` | Removes featured from all, sets this one |

### UserProfileController
**Purpose:** Edit profile. Shared by all roles via role-prefixed routes.

| Method | Notes |
|--------|-------|
| `edit()` | Loads user and profile, creates profile if none exists |
| `update(Request)` | Updates user and profile, handles profile image upload |

### NotificationController
**Purpose:** Handle notification interactions.

| Method | Notes |
|--------|-------|
| `read(string $id)` | Marks notification as read, redirects to relevant resource using `routePrefix()` |
| `markAllRead()` | Marks all unread notifications as read |

### WebhookController
**Purpose:** Handle Stripe webhook events. Extends `CashierWebhookController`.

| Handler | Trigger | Action |
|---------|---------|--------|
| `handleCheckoutSessionCompleted` | Stripe checkout paid | Updates payment to `paid`, notifies tenant/manager/admins |
| `handlePaymentIntentPaymentFailed` | Stripe payment failed | Updates payment to `failed`, notifies all parties |

---

## Manager Controllers

### Manager\PropertyController
| Method | Notes |
|--------|-------|
| `index()` | Only shows properties owned by authenticated manager |
| `create()` | Loads amenities |
| `store(Request)` | Creates property, handles image uploads, attaches amenities |
| `show(Property)` | Loads images, amenities, leases with tenant |
| `edit(Property)` | Authorises via `PropertyPolicy::update` |
| `update(Request, Property)` | Syncs amenities via `sync()` |
| `destroy(Property)` | Authorises via `PropertyPolicy::delete` |

### Manager\LeaseController
| Method | Notes |
|--------|-------|
| `store(Request)` | Creates lease, sets property to `occupied`, auto-generates payments via `LeasePaymentService` if status is `active` |
| `update(Request, Lease)` | Captures old status, cancels future payments if terminated/ended, generates payments if newly activated |

### Manager\DocumentController
| Method | Notes |
|--------|-------|
| `store(Request)` | Stores file on `private` disk, notifies tenant via `DocumentUploadedNotification` |
| `destroy(Document)` | Deletes file from storage and DB |

### Manager\WorkOrderController
| Method | Notes |
|--------|-------|
| `store(Request)` | Creates work order, notifies property manager via `WorkOrderCreatedNotification` |
| `update(Request, WorkOrder)` | Captures old status, fires `WorkOrderUpdatedNotification` if status changed |

### Manager\ConversationController
| Method | Notes |
|--------|-------|
| `index()` | Loads conversations where `property_manager_id = auth()->id()` |
| `show(Conversation)` | Checks ownership directly, marks unread messages as read |

### Manager\PaymentController
| Method | Notes |
|--------|-------|
| `store(Request)` | Creates payment, notifies tenant via `PaymentDueNotification` |
| `markPaid(Payment)` | Sets status to `paid` |
| `updateStatus(Request, Payment)` | Manual status override, fires success/failed notification if status changed |

---

## Tenant Controllers

### Tenant\LeaseController
| Method | Notes |
|--------|-------|
| `index()` | Only shows leases where `tenant_id = auth()->id()` |
| `show(Lease)` | Authorises via `LeasePolicy::view` |

### Tenant\DocumentController
| Method | Notes |
|--------|-------|
| `index()` | Only shows documents where `tenant_id = auth()->id()` |
| `sign(Document)` | Sets `is_signed`, `signed_at`, fires `DocumentSignedNotification` to uploader |

### Tenant\WorkOrderController
| Method | Notes |
|--------|-------|
| `create()` | Loads only active leases for the tenant |
| `store(Request)` | Creates work order, notifies property manager |

### Tenant\ConversationController
| Method | Notes |
|--------|-------|
| `show(Conversation)` | Checks `tenant_id === auth()->id()`, marks unread messages as read |

### Tenant\PaymentController
| Method | Notes |
|--------|-------|
| `checkout(Payment)` | Creates Stripe checkout session, stores session ID on payment |
| `success(Request, Payment)` | Verifies payment with Stripe directly (fallback to webhook), updates status, fires notifications |

---

## Admin Controllers

### Admin\UserController
| Method | Notes |
|--------|-------|
| `store(Request)` | Creates user, assigns role, creates empty profile |
| `update(Request, User)` | Uses `syncRoles()` to update role |
| `destroy(User)` | Prevents self-deletion |
| `assignTenant(Request, User)` | Attaches tenant to manager via pivot, checks for duplicates |
| `removeTenant(User, User)` | Detaches tenant from manager |

### Admin\PaymentController
| Method | Notes |
|--------|-------|
| `index()` | Shows all payments with stats (total paid, pending, failed count) |
| `updateStatus(Request, Payment)` | Manual status override with notification |
