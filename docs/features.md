# Rently — Feature Progress

> Feature tracker and branch reference.
> Last updated: May 2026

---

## Completed Features

| Feature | Branch | Notes |
|---------|--------|-------|
| Database schema & migrations | `database` | All tables, foreign keys, indexes |
| Seed data | `database` | Users, properties, amenities, leases, documents, messages, work orders |
| Eloquent models & relationships | `feature/eloquent-models` | All models with full relationship definitions |
| Spatie roles | `feature/eloquent-models` | admin, property_manager, tenant — seeded via RoleSeeder |
| Route protection & middleware | `feature/auth-middleware` | Role-based route groups, dashboard smart redirect |
| Authorization policies | `feature/auth-middleware` | Property, Lease, Document, WorkOrder, Message policies |
| Property CRUD — public | `feature/property-crud` | Public listings and single property view |
| Property CRUD — manager & admin | `feature/property-crud` | Full CRUD with amenity sync |
| Dashboard structure | `feature/property-crud` | Admin, manager, tenant dashboards with portal layout |
| Messaging system | `feature/messaging` | Conversations, messages, read receipts, system messages |
| Lease management | `feature/lease-management` | Full CRUD for manager and admin, read-only for tenant |
| Work orders | `feature/work-orders` | Create, update, status tracking, update thread |
| Documents with signing | `feature/documents` | Upload, download, simple electronic signature flow |
| Tenant dashboard | `feature/tenant-dashboard` | Overview of lease, documents, work orders, messages |
| User profile management | `feature/user-profiles` | Edit profile, avatar upload, emergency contact |
| Navigation | `feature/navigation` | Wired nav links, active states, profile avatar, role badge |
| Notifications | `feature/notifications` | 10 notification types, bell icon, mark as read, redirects |
| Admin user management | `feature/admin-user-management` | Create, edit, delete users, assign tenants to managers |
| Payments — Stripe | `feature/payments` | Stripe Checkout, webhook handling, success verification |
| Payments — Manual | `feature/payments` | Manual payment tracking, mark as paid |
| Payments — Auto-generation | `feature/payments` | LeasePaymentService, scheduled commands |
| Property image uploads | `feature/property-images` | Multiple upload, featured image, delete |

---

## Pending / In Progress

| Feature | Priority | Notes |
|---------|----------|-------|
| Mobile navigation | Medium | Hamburger menu for screens below `md` breakpoint |
| Admin dashboard stats | Medium | System-wide overview — users, properties, leases, revenue |
| Manager dashboard optimisation | Low | Optimise active lease count query |
| Property search & filtering | Medium | Filter by price, bedrooms, type, availability on public listings |
| Email notifications | Low | Add mail channel to existing notification classes |
| Password change from profile | Low | Currently links to password reset — could be inline form |
| Breadcrumbs | Low | Portal navigation breadcrumbs |

---

## Deferred

| Feature | Notes |
|---------|-------|
| Subscription management | Full Stripe subscription objects rather than one-off checkout sessions |
| Refund processing | Trigger refunds via Stripe API |
| Invoice PDF generation | Auto-generate PDF invoices for each payment |
| Tenant application flow | "Express Interest" button on public listing → application form |
| Saved properties | Tenant can save/favourite properties from public listing |
| Property availability calendar | Visual calendar of availability |
| Maintenance contractor management | Assign work orders to external contractors |
| Bulk messaging | Send message to all tenants at once |
| Reporting & exports | CSV/PDF exports of payments, leases, occupancy |

---

## Branch Strategy

```
main
└── develop
    ├── feature/eloquent-models
    ├── feature/auth-middleware
    ├── feature/property-crud
    ├── feature/messaging
    ├── feature/lease-management
    ├── feature/work-orders
    ├── feature/documents
    ├── feature/tenant-dashboard
    ├── feature/user-profiles
    ├── feature/navigation
    ├── feature/notifications
    ├── feature/admin-user-management
    ├── feature/payments
    └── feature/property-images
```

All feature branches are merged into `develop`. Merge `develop` into `main` for releases.

---

## Seeders

| Seeder | Purpose |
|--------|---------|
| `RoleSeeder` | Creates admin, property_manager, tenant roles |
| `UserSeeder` | Creates 3 test users, assigns roles, creates profiles, assigns tenant to manager |
| `AmenitySeeder` | Seeds 10 amenities |
| `PropertySeeder` | Seeds 2 properties assigned to the manager |
| `AmenityPropertySeeder` | Assigns amenities to seeded properties |
| `LeaseSeeder` | Creates an active lease linking tenant to property |
| `DocumentSeeder` | Seeds 2 documents — one requiring signature |
| `WorkOrderSeeder` | Seeds a work order with 2 updates |
| `MessageSeeder` | Seeds a conversation with 3 messages including a system message |

Run order in `DatabaseSeeder`:
```
RoleSeeder → UserSeeder → AmenitySeeder → PropertySeeder →
AmenityPropertySeeder → LeaseSeeder → DocumentSeeder →
WorkOrderSeeder → MessageSeeder
```

---

## Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@rently.com | password |
| Property Manager | manager@rently.com | password |
| Tenant | tenant@rently.com | password |
