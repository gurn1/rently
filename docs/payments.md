# Rently — Payments

> Payment system built on Laravel Cashier + Stripe.
> Last updated: May 2026

---

## Overview

Rently supports two payment methods:
- **Stripe** — online card payments via Stripe Checkout
- **Manual** — bank transfer or cash, marked as paid by the property manager

Payments are generated automatically when a lease becomes active, covering the full lease term. For open-ended leases, payments are extended 3 months at a time via a scheduled command.

---

## Setup

**Install Cashier:**
```bash
composer require laravel/cashier
php artisan vendor:publish --tag="cashier-migrations"
php artisan migrate
```

**Add `Billable` trait to `User` model:**
```php
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, Billable;
}
```

**Environment variables:**
```env
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

**Stripe CLI for local webhook testing:**
```bash
stripe listen --forward-to localhost:8000/stripe/webhook
```

---

## Payment Flow

### Stripe Payments

```
Manager creates payment request
    ↓
Tenant receives PaymentDueNotification
    ↓
Tenant clicks "Pay Now" on payments index or show page
    ↓
Tenant/PaymentController@checkout creates Stripe Checkout session
Checkout session ID stored on payment as stripe_payment_intent_id
    ↓
Tenant redirected to Stripe hosted checkout page
    ↓
Tenant completes payment on Stripe
    ↓
Stripe redirects to /tenant/payments/{payment}/success
    ↓
Tenant/PaymentController@success verifies session with Stripe API
Payment updated to paid, paid_at set, stripe_payment_intent_id updated with real payment intent ID
PaymentSuccessfulNotification fired to tenant, manager, all admins
    ↓
Stripe also fires webhook → WebhookController@handleCheckoutSessionCompleted (backup)
```

### Manual Payments

```
Manager creates payment request with method = manual
    ↓
Tenant receives PaymentDueNotification
    ↓
Tenant pays via bank transfer or cash (outside the system)
    ↓
Manager marks payment as paid via "Mark Paid" button
    ↓
Payment updated to paid, paid_at set
```

---

## Auto-Generated Payments

When a lease is created or updated to `active` status, `LeasePaymentService::generatePayments()` is called automatically.

**File:** `app/Services/LeasePaymentService.php`

### `generatePayments(Lease $lease, string $paymentMethod)`
- Generates one payment per month from `start_date` to `end_date`
- If no `end_date`, generates 12 months ahead
- Skips months where a payment already exists (duplicate safe)
- Payment `due_date` is set to the first of each month

### `extendPayments(Lease $lease, int $months = 3)`
- Called by the `payments:extend-leases` scheduled command
- Finds the last payment's due date and generates `$months` more
- Used for open-ended leases with no `end_date`

### `cancelFuturePayments(Lease $lease)`
- Called when a lease status changes to `ended` or `terminated`
- Deletes all `pending` payments with a `due_date` in the future

---

## Scheduled Commands

### `payments:send-reminders`
**File:** `app/Console/Commands/SendPaymentReminders.php`
**Schedule:** Daily at 09:00

Finds all pending payments due within the next 3 days and sends `PaymentDueNotification` to the tenant.

```bash
php artisan payments:send-reminders
```

### `payments:extend-leases`
**File:** `app/Console/Commands/ExtendLeasePayments.php`
**Schedule:** Monthly on the 1st at 00:00

Finds all active leases with no `end_date` and extends their payments by 3 months using `LeasePaymentService::extendPayments()`.

```bash
php artisan payments:extend-leases
```

**Registered in `routes/console.php`:**
```php
Schedule::command('payments:send-reminders')->dailyAt('09:00');
Schedule::command('payments:extend-leases')->monthlyOn(1, '00:00');
```

**Run the scheduler locally:**
```bash
php artisan schedule:work
```

---

## Manual Status Override

Both managers and admins can manually update a payment status via the payment show page. Useful for correcting errors or handling edge cases.

Available statuses: `pending`, `paid`, `failed`, `refunded`

When status is changed:
- If updated to `paid` → fires `PaymentSuccessfulNotification` to tenant
- If updated to `failed` → fires `PaymentFailedNotification` to tenant

---

## Failed Payments

When a payment fails (either via Stripe webhook or manual status update):
- Payment status set to `failed`
- `PaymentFailedNotification` sent to tenant, property manager, and all admins
- A warning banner appears on the tenant dashboard until resolved
- Access is not suspended — tenant retains full portal access

---

## Webhook Handling

**File:** `app/Http/Controllers/WebhookController.php`

Extends `Laravel\Cashier\Http\Controllers\WebhookController`.

| Stripe Event | Handler | Action |
|---|---|---|
| `checkout.session.completed` | `handleCheckoutSessionCompleted` | Mark payment paid, notify all parties |
| `payment_intent.payment_failed` | `handlePaymentIntentPaymentFailed` | Mark payment failed, notify all parties |

**CSRF exempt route:**
```php
Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);
```

Excluded from CSRF in `bootstrap/app.php`:
```php
$middleware->validateCsrfTokens(except: ['stripe/webhook']);
```

---

## Payment Method Selection

When creating a lease, the property manager selects the payment method. This is stored on each generated payment. The payment method determines:

- **Stripe** — tenant sees a "Pay Now" button that initiates Stripe Checkout
- **Manual** — tenant sees payment details only; manager marks as paid manually

The payment method is carried forward when payments are extended for open-ended leases.

---

## Key Notes

- Stripe amounts are in **pence** (GBP minor unit) — `£12.00 = 1200`
- The `stripe_payment_intent_id` column stores the **checkout session ID** initially, then is replaced with the actual **payment intent ID** on success
- The success page verifies the payment directly with Stripe as a fallback in case the webhook hasn't fired yet
- All payments are in GBP by default (`currency = 'gbp'`)
