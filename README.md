# Rently — Housing Rental Portal

A full-stack housing rental portal built in Laravel 13. Supports three user types — Tenant, Property Manager, and Administrator — each with their own dashboard, permissions, and workflows.

---

## Tech Stack

- **Framework** — Laravel 13
- **Auth scaffolding** — Laravel Breeze
- **Roles & permissions** — Spatie Laravel Permission
- **Payments** — Laravel Cashier + Stripe
- **Frontend** — Blade + Tailwind CSS + Alpine.js
- **Database** — MySQL
- **Storage** — Laravel local disk (public + private)
- **Queue** — Laravel queue (database driver)

---

## Requirements

- PHP 8.4+
- Composer
- Node.js 18+
- MySQL 8+
- Stripe account (test mode keys)

---

## Installation

**1. Clone the repository**
```bash
git clone <repo-url> rently
cd rently
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Install Node dependencies**
```bash
npm install
```

**4. Copy environment file**
```bash
cp .env.example .env
```

**5. Generate application key**
```bash
php artisan key:generate
```

**6. Configure your `.env`**
```env
APP_NAME=Rently
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rently
DB_USERNAME=root
DB_PASSWORD=

STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

**7. Run migrations and seed**
```bash
php artisan migrate --seed
```

**8. Create storage symlink**
```bash
php artisan storage:link
```

**9. Build frontend assets**
```bash
npm run dev
```

**10. Start the development server**
```bash
composer run dev
```

---

## Seeded Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@rently.com | password |
| Property Manager | manager@rently.com | password |
| Tenant | tenant@rently.com | password |

---

## Branch Structure

| Branch | Purpose |
|--------|---------|
| `main` | Production ready code |
| `develop` | Integration branch |
| `feature/*` | Feature branches |
| `fix/*` | Bug fix branches |

Always branch from `develop`. Merge feature branches back into `develop`. Merge `develop` into `main` for releases.

---

## Key Commands

```bash
# Run all migrations fresh with seed data
php artisan migrate:fresh --seed

# Send payment due reminders (run daily via scheduler)
php artisan payments:send-reminders

# Extend payments for open-ended leases (run monthly via scheduler)
php artisan payments:extend-leases

# Run the scheduler locally
php artisan schedule:work

# Forward Stripe webhooks locally
stripe listen --forward-to localhost:8000/stripe/webhook
```

---

## Documentation Index

| File | Contents |
|------|---------|
| [schema.md](docs/schema.md) | Database tables, columns, ERD diagram |
| [models.md](docs/models.md) | Eloquent models and relationships |
| [routes.md](docs/routes.md) | Routes, middleware, naming conventions |
| [controllers.md](docs/controllers.md) | Controller index by role |
| [policies.md](docs/policies.md) | Authorization policies per model |
| [notifications.md](docs/notifications.md) | Notification classes and triggers |
| [views.md](docs/views.md) | View structure and layout system |
| [payments.md](docs/payments.md) | Payment flow, Stripe integration, scheduled commands |
| [features.md](docs/features.md) | Feature progress and branch reference |
