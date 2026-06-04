# Government Press Printing Services — Implementation Summary

This document summarises the work completed on the **Department of Printing Services** Laravel application: the admin panel build (per the agreed plan), hybrid commerce flows, ERP integration scaffolding, and XAMPP deployment fixes.

---

## Overview

The site was a **config-driven public brochure** (Laravel 12 + Breeze) with forward-looking database migrations but no operational backend. It is now a **three-surface application**:

| Surface | URL | Purpose |
|---------|-----|---------|
| Public website | `/` | Marketing, products, services, cart, quotation requests |
| Client portal | `/dashboard`, `/portal/*` | Customers track quotes, orders, and print jobs |
| Admin panel | `/admin` | Staff CMS, catalog, sales, production, ERP tools |

**Commerce model (hybrid):**

- **Complex jobs** → quotation workflow (review → approve → print job → ERP)
- **Simple products** (calendars, flyers, etc.) → cart → checkout → order

---

## Packages Installed

| Package | Role |
|---------|------|
| `filament/filament` v3.3 | Admin UI at `/admin` |
| `spatie/laravel-permission` | Roles and permissions |
| `bezhansalleh/filament-shield` | Filament RBAC integration |

---

## Architecture

```
Public Site          Client Portal (Breeze)       Admin Panel (Filament)
───────────          ──────────────────────       ──────────────────────
Pages, cart,    →    My quotations, orders,   ←   CMS, catalog, quotes,
quote form           print jobs, artwork            orders, jobs, ERP sync
                              │
                              ▼
                    Internal ERP (future)
                    pull products/services
                    push quotes/orders
```

---

## Database & Models

### New migrations (June 2026)

| Migration | Purpose |
|-----------|---------|
| `add_is_staff_to_users_table` | Gates Filament access |
| `create_products_table` | Simple purchasable catalogue |
| `create_carts_table` / `cart_items` | Session + user carts |
| `create_orders_table` / `order_items` | Simple product orders |
| `create_site_settings_table` | DB-backed site configuration |
| `add_erp_columns_to_catalog_and_quotations` | ERP fields on services & quotations |
| `create_erp_sync_logs_table` | ERP sync audit trail |
| `create_permission_tables` | Spatie roles/permissions |

### Existing migrations wired up

Organizations, customer profiles, service categories, services, quotations, quotation items, print jobs, job status logs, artwork files, pages, documents, vacancies.

### Models implemented

All Eloquent models were fleshed out with relationships, fillable fields, and helpers. Notable renames:

- `Job` → **`PrintJob`** (avoids clash with Laravel queue `jobs` table)
- Reference generators: `QUO-YYYY-####`, `ORD-YYYY-####`, `JOB-YYYY-####`

### Seeders

| Seeder | Contents |
|--------|----------|
| `RoleAndPermissionSeeder` | 6 roles + granular permissions |
| `AdminUserSeeder` | Staff admin + test customer |
| `CatalogSeeder` | Services & products from `config/homepage.php` |
| `SiteSettingsSeeder` | Contact, brand, meta into DB |

Run with: `php artisan db:seed`

---

## Role-Based Access Control (RBAC)

| Role | Access |
|------|--------|
| `super_admin` | Full admin access |
| `content_manager` | CMS, site settings, documents, vacancies |
| `sales_officer` | Quotations, customers, organizations, orders |
| `production_manager` | Print jobs, artwork, status tracking |
| `finance_officer` | Quotes/orders read + ERP push/sync |
| `customer` | Client portal only (no Filament) |

Staff users require `is_staff = true` on the `users` table. Filament access is enforced via `User::canAccessPanel()`.

---

## Admin Panel (`/admin`)

**Provider:** `app/Providers/Filament/AdminPanelProvider.php`

### Filament resources (57 files)

| Group | Resources |
|-------|-----------|
| **Administration** | Users |
| **CMS** | Pages, Documents, Vacancies, Site Settings (custom page) |
| **Catalog** | Service Categories, Services, Products |
| **Customers** | Organizations, Customer Profiles |
| **Sales** | Quotations (+ line items), Orders (+ line items) |
| **Production** | Print Jobs, Artwork Files |
| **ERP Integration** | ERP Dashboard (custom page) |

### Dashboard widget

`DashboardStatsWidget` — pending quotations, active print jobs, recent orders (7 days).

### Quotation workflow (admin actions)

On the quotation edit screen:

1. **Approve** — sets status to `approved`
2. **Reject** — requires rejection reason
3. **Convert to Job** — creates `PrintJob`, sets status to `converted_to_job`
4. **Push to ERP** — sends payload via `ErpSyncService`

Line items auto-calculate VAT (17.5%) and totals via `QuotationItemObserver`.

### Production

- `PrintJobObserver` logs every status change to `job_status_logs`
- Pipeline: `queued → prepress → printing → finishing → quality_check → ready → delivered`

### Site Settings page

Edits contact info, brand, and SEO stored in `site_settings`. Public site merges DB values with `config/homepage.php` fallback via `SiteContentService`.

---

## Public Website Changes

### Controllers & services

| File | Role |
|------|------|
| `PublicPageController` | Uses `SiteContentService` + live cart count |
| `QuotationController` | Persists quote requests (login required) |
| `CartController` | Add / update / remove cart items |
| `CheckoutController` | Creates orders from cart with VAT |
| `SiteContentService` | DB + config merge for pages, services, products |
| `CartService` | Session cart (guests) / DB cart (logged-in), merge on login |

### Updated views

- `pages/quotation.blade.php` — server POST form (replaces mailto)
- `pages/cart.blade.php` — dynamic cart with checkout
- `pages/products.blade.php` — “Add to Cart” buttons with prices

### New routes

```
POST /quotation              quotation.store
POST /cart/add/{product}     cart.add
PATCH /cart/items/{item}     cart.update
DELETE /cart/items/{item}    cart.remove
POST /checkout               checkout.store (auth)
```

---

## Client Portal

Extended Breeze dashboard with dedicated portal routes:

| Route | Purpose |
|-------|---------|
| `/dashboard` | Portal home with quick links |
| `/portal/quotations` | List own quotations |
| `/portal/quotations/{id}` | View quote + upload artwork |
| `/portal/orders` | Order history |
| `/portal/orders/{id}` | Order detail |
| `/portal/print-jobs` | Production tracking |
| `/portal/print-jobs/{id}` | Job status history |

New registrations automatically receive the `customer` role and a `customer_profiles` row.

---

## ERP Integration (scaffolding)

**Config:** `config/erp.php` + `.env` placeholders

```
ERP_SYNC_ENABLED=false
ERP_BASE_URL=
ERP_API_KEY=
ERP_TIMEOUT=30
ERP_WEBHOOK_SECRET=
```

### Service layer (`app/Services/Erp/`)

| Class | Role |
|-------|------|
| `ErpClientInterface` | Contract for pull/push operations |
| `NullErpClient` | Dev stub (active when ERP disabled) |
| `HttpErpClient` | REST adapter for production |
| `ErpSyncService` | Orchestration + sync logging |

### Queue jobs

- `SyncProductsFromErp`
- `SyncServicesFromErp`
- `PushQuotationToErp`
- `PushOrderToErp`

Scheduled daily when `ERP_SYNC_ENABLED=true` (`routes/console.php`).

### Admin ERP Dashboard

Manual sync buttons + paginated sync log table (`erp_sync_logs`).

**Note:** The internal ERP API spec is not yet connected. The abstraction layer is ready — enable via `.env` once endpoints are available.

---

## XAMPP / Deployment Fixes

### Problem

- Root URL showed an Apache **directory listing**
- Users had to navigate manually to `/public`
- **Filament admin CSS** did not load (wrong `APP_URL` and unpublished assets)

### Solution

| Change | File |
|--------|------|
| Root rewrite to `public/` | `.htaccess` (project root) |
| Root bootstrap fallback | `index.php` (project root) |
| Subdirectory rewrite base | `public/.htaccess` |
| Legacy URL redirect | `/public/*` → `/*` (301) |
| Correct base URL | `.env` → `APP_URL=http://localhost/printingservices.gov.mw` |
| Force asset URLs | `AppServiceProvider` → `URL::forceRootUrl()` |
| Vite subdirectory base | `vite.config.js` → `base: '/printingservices.gov.mw/'` |
| Filament assets published | `php artisan filament:assets` |
| Auto-publish on composer update | `composer.json` post-autoload-dump hook |

### Correct URLs

| Page | URL |
|------|-----|
| Homepage | `http://localhost/printingservices.gov.mw/` |
| Admin login | `http://localhost/printingservices.gov.mw/admin` |
| Client portal | `http://localhost/printingservices.gov.mw/dashboard` |

Do **not** include `/public` in URLs.

---

## Default Login Credentials

| Account | Email | Password | Access |
|---------|-------|----------|--------|
| Admin | `admin@printing.gov.mw` | `password` | `/admin` (staff) |
| Test customer | `test@example.com` | `password` | `/dashboard` (client) |

**Change these passwords before any production use.**

---

## Key File Locations

```
app/
  Filament/              Admin resources, pages, widgets
  Http/Controllers/      Public, cart, checkout, portal, quotation
  Jobs/                  ERP sync queue jobs
  Listeners/             MergeGuestCart on login
  Models/                All domain models
  Observers/             PrintJob, QuotationItem
  Providers/
    Filament/AdminPanelProvider.php
  Services/
    CartService.php
    SiteContentService.php
    Erp/                 ERP abstraction layer

config/
  erp.php
  homepage.php           Static fallback content

database/
  migrations/            Domain + new commerce tables
  seeders/               Roles, admin, catalog, settings

resources/views/
  pages/                 Public cart, quotation, products
  portal/                Client portal views
  filament/pages/        Site settings, ERP dashboard

routes/
  web.php                All public + portal routes
  console.php            Scheduled ERP sync
```

---

## Setup Commands (reference)

```bash
composer install
npm install
cp .env.example .env        # if fresh
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan filament:assets
npm run build
php artisan storage:link
```

For queued ERP jobs: `php artisan queue:work`

---

## Not Yet Implemented (future work)

- Email notifications for quotation status changes
- Payment gateway integration
- ERP webhook endpoint for inbound job status updates
- Full migration of all `homepage.php` sections to DB (hero slides, navigation, etc.)
- Filament Shield policy UI fully wired per resource
- Automated test coverage for commerce workflows

---

## VAT

Malawi VAT rate **17.5%** is applied consistently across quotations, quotation line items, orders, and order items.

---

*Document generated to reflect implementation completed on the Government Press Printing Services platform.*
