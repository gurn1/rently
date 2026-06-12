# Rently — Routes & Middleware

> All routes defined in `routes/web.php`.
> Last updated: May 2026

---

## Route Groups

| Group | Middleware | Prefix | Name Prefix |
|-------|-----------|--------|-------------|
| Public | none | `/` | — |
| Auth (Breeze) | — | — | — |
| Tenant | `auth, role:tenant` | `/tenant` | `tenant.` |
| Property Manager | `auth, role:property_manager` | `/manager` | `manager.` |
| Admin | `auth, role:admin` | `/admin` | `admin.` |
| Shared auth | `auth` | — | — |

---

## Public Routes

```
GET  /                    welcome page
GET  /properties          PropertyController@index
GET  /properties/{slug}   PropertyController@show
POST /stripe/webhook      WebhookController@handleWebhook  (CSRF exempt)
```

---

## Dashboard Redirect

```
GET /dashboard   → redirects to role-specific dashboard
```

Logic:
- `admin` → `/admin/dashboard`
- `property_manager` → `/manager/dashboard`
- `tenant` → `/tenant/dashboard`

---

## Tenant Routes (`/tenant/*`)

```
GET   /tenant/dashboard

GET   /tenant/leases
GET   /tenant/leases/{lease}

GET   /tenant/documents
GET   /tenant/documents/{document}
POST  /tenant/documents/{document}/sign

GET   /tenant/work-orders
GET   /tenant/work-orders/create
POST  /tenant/work-orders
GET   /tenant/work-orders/{work-order}
POST  /tenant/work-orders/{workOrder}/updates

GET   /tenant/messages
GET   /tenant/messages/{conversation}
POST  /tenant/messages/{conversation}

GET   /tenant/payments
GET   /tenant/payments/{payment}
GET   /tenant/payments/{payment}/checkout
GET   /tenant/payments/{payment}/success

GET   /tenant/profile
PUT   /tenant/profile

GET   /notifications/{id}/read
POST  /notifications/mark-all-read
```

---

## Property Manager Routes (`/manager/*`)

```
GET   /manager/dashboard

GET   /manager/properties
GET   /manager/properties/create
POST  /manager/properties
GET   /manager/properties/{property}
GET   /manager/properties/{property}/edit
PUT   /manager/properties/{property}
DELETE /manager/properties/{property}
POST  /manager/properties/{property}/images
DELETE /manager/properties/{property}/images/{image}
POST  /manager/properties/{property}/images/{image}/featured

GET   /manager/leases
GET   /manager/leases/create
POST  /manager/leases
GET   /manager/leases/{lease}
GET   /manager/leases/{lease}/edit
PUT   /manager/leases/{lease}
DELETE /manager/leases/{lease}

GET   /manager/documents
GET   /manager/documents/create
POST  /manager/documents
GET   /manager/documents/{document}
DELETE /manager/documents/{document}

GET   /manager/work-orders
GET   /manager/work-orders/create
POST  /manager/work-orders
GET   /manager/work-orders/{work-order}
GET   /manager/work-orders/{work-order}/edit
PUT   /manager/work-orders/{work-order}
DELETE /manager/work-orders/{work-order}
POST  /manager/work-orders/{workOrder}/updates

GET   /manager/messages
GET   /manager/messages/{conversation}
POST  /manager/messages/{conversation}

GET   /manager/payments
GET   /manager/payments/create
POST  /manager/payments
GET   /manager/payments/{payment}
POST  /manager/payments/{payment}/mark-paid
POST  /manager/payments/{payment}/status

GET   /manager/profile
PUT   /manager/profile
```

---

## Admin Routes (`/admin/*`)

```
GET   /admin/dashboard

GET   /admin/properties
GET   /admin/properties/create
POST  /admin/properties
GET   /admin/properties/{property}
GET   /admin/properties/{property}/edit
PUT   /admin/properties/{property}
DELETE /admin/properties/{property}
POST  /admin/properties/{property}/images
DELETE /admin/properties/{property}/images/{image}
POST  /admin/properties/{property}/images/{image}/featured

GET   /admin/leases
GET   /admin/leases/create
POST  /admin/leases
GET   /admin/leases/{lease}
GET   /admin/leases/{lease}/edit
PUT   /admin/leases/{lease}
DELETE /admin/leases/{lease}

GET   /admin/documents
GET   /admin/documents/{document}

GET   /admin/work-orders
GET   /admin/work-orders/{work-order}

GET   /admin/payments
GET   /admin/payments/{payment}
POST  /admin/payments/{payment}/status

GET   /admin/users
GET   /admin/users/create
POST  /admin/users
GET   /admin/users/{user}
GET   /admin/users/{user}/edit
PUT   /admin/users/{user}
DELETE /admin/users/{user}
POST  /admin/users/{user}/assign-tenant
DELETE /admin/users/{user}/remove-tenant/{tenant}

GET   /admin/profile
PUT   /admin/profile
```

---

## Shared Auth Routes

```
GET   /profile        Breeze ProfileController@edit
PATCH /profile        Breeze ProfileController@update
DELETE /profile       Breeze ProfileController@destroy

GET   /notifications/{id}/read     NotificationController@read
POST  /notifications/mark-all-read NotificationController@markAllRead
```

---

## Naming Convention

Routes follow Laravel's named route convention with role prefix:

```php
route('tenant.dashboard')
route('manager.properties.index')
route('admin.users.show', $user)
route('tenant.payments.checkout', $payment)
```

Resource routes are explicitly named inside each group to ensure the role prefix is applied correctly:

```php
Route::resource('properties', ManagerPropertyController::class)
    ->names([
        'index'  => 'properties.index',   // → manager.properties.index
        'create' => 'properties.create',  // → manager.properties.create
        // etc
    ]);
```

---

## Role-based Redirects After Login

Handled in `app/Http/Controllers/Auth/AuthenticatedSessionController@store`:

```php
return redirect()->intended(route('dashboard'));
```

The `/dashboard` route then redirects based on role.

---

## CSRF Exemption

The Stripe webhook route is exempt from CSRF verification. Configured in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->validateCsrfTokens(except: [
        'stripe/webhook',
    ]);
})
```
