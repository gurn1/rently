# Rently — Notifications

> All notification classes live in `app/Notifications/`.
> Uses Laravel's built-in database channel.
> Last updated: May 2026

---

## Overview

Notifications are stored in the `notifications` table and displayed in the portal via a bell icon in the nav. Clicking a notification marks it as read and redirects to the relevant resource.

All notifications use the `database` channel. Email channel can be added later by adding `'mail'` to the `via()` array and implementing `toMail()`.

---

## Notification Classes

| Class | Trigger | Recipient(s) |
|-------|---------|-------------|
| `NewMessageNotification` | Message sent | Other party in conversation |
| `WorkOrderCreatedNotification` | Work order raised | Property manager of the property |
| `WorkOrderUpdatedNotification` | Work order status changed | User who raised the work order |
| `WorkOrderCommentNotification` | Update/comment added | Other party on the work order |
| `DocumentUploadedNotification` | Document uploaded | Assigned tenant |
| `DocumentSignedNotification` | Document signed by tenant | Uploading property manager |
| `LeaseStatusChangedNotification` | Lease status updated | Tenant on the lease |
| `PaymentSuccessfulNotification` | Payment confirmed | Tenant, property manager, all admins |
| `PaymentFailedNotification` | Payment failed | Tenant, property manager, all admins |
| `PaymentDueNotification` | Payment due reminder | Tenant |

---

## Data Payload

Each notification stores a JSON `data` payload. The `type` key is used by `NotificationController` to build the redirect URL.

### NewMessageNotification
```json
{
    "message": "John sent you a message.",
    "preview": "First 100 chars of message body...",
    "conversation_id": 1,
    "sender_id": 2,
    "type": "new_message"
}
```

### WorkOrderCreatedNotification
```json
{
    "message": "A new work order has been raised: Boiler not heating water",
    "work_order_id": 1,
    "property_id": 1,
    "raised_by": "Jane Doe",
    "priority": "high",
    "type": "work_order_created"
}
```

### WorkOrderUpdatedNotification
```json
{
    "message": "Work order \"Boiler\" status changed from open to in_progress.",
    "work_order_id": 1,
    "old_status": "open",
    "new_status": "in_progress",
    "type": "work_order_updated"
}
```

### WorkOrderCommentNotification
```json
{
    "message": "John added an update to work order \"Boiler\".",
    "preview": "First 100 chars of comment...",
    "work_order_id": 1,
    "user_id": 2,
    "type": "work_order_comment"
}
```

### DocumentUploadedNotification
```json
{
    "message": "A new document has been shared with you: Tenancy Agreement 2026",
    "document_id": 1,
    "requires_signature": true,
    "type": "document_uploaded"
}
```

### DocumentSignedNotification
```json
{
    "message": "Jane has signed \"Tenancy Agreement 2026\".",
    "document_id": 1,
    "signed_at": "2026-05-01T10:00:00Z",
    "type": "document_signed"
}
```

### LeaseStatusChangedNotification
```json
{
    "message": "Your lease status for 2 Bed Apartment changed from pending to active.",
    "lease_id": 1,
    "old_status": "pending",
    "new_status": "active",
    "type": "lease_status_changed"
}
```

### PaymentSuccessfulNotification
```json
{
    "message": "Payment of £1200.00 received successfully.",
    "payment_id": 1,
    "lease_id": 1,
    "amount": 1200.00,
    "type": "payment_successful"
}
```

### PaymentFailedNotification
```json
{
    "message": "Payment of £1200.00 failed. Please update your payment details.",
    "payment_id": 1,
    "lease_id": 1,
    "amount": 1200.00,
    "type": "payment_failed"
}
```

### PaymentDueNotification
```json
{
    "message": "Rent payment of £1200.00 is due on 01/06/2026.",
    "payment_id": 1,
    "lease_id": 1,
    "amount": 1200.00,
    "due_date": "2026-06-01T00:00:00Z",
    "type": "payment_due"
}
```

---

## Notification Redirects

`NotificationController@read` marks the notification as read and redirects based on `type`:

| Type | Redirect |
|------|---------|
| `new_message` | `{role}.messages.show` |
| `work_order_created`, `work_order_updated`, `work_order_comment` | `{role}.work-orders.show` |
| `document_uploaded`, `document_signed` | `{role}.documents.show` |
| `lease_status_changed` | `{role}.leases.show` |
| `payment_successful`, `payment_failed`, `payment_due` | `{role}.payments.show` |
| anything else | `dashboard` |

The `{role}` prefix is resolved via `auth()->user()->routePrefix()`.

---

## Bell Icon

The notification bell lives in `layouts/portal.blade.php` and uses Alpine.js for the dropdown toggle:

```html
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open">...</button>
    <div x-show="open" @click.away="open = false">...</div>
</div>
```

Unread count is loaded from `auth()->user()->unreadNotifications->count()`.

---

## Adding Email Notifications

To add email to any notification, update `via()` and add `toMail()`:

```php
public function via(object $notifiable): array
{
    return ['database', 'mail'];
}

public function toMail(object $notifiable): MailMessage
{
    return (new MailMessage)
        ->subject('New message from Rently')
        ->line($this->message->sender->first_name . ' sent you a message.')
        ->action('View Message', route($notifiable->routePrefix() . '.messages.show', $this->conversation));
}
```
