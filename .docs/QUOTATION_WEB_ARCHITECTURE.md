# Website quotation flow — web architecture (Phase 1)

This document maps the [quotation specification](.) to the Laravel implementation. **ERP import is stubbed** until Press ERP exposes `POST /api/v1/quotations/import`.

## Surfaces

| Surface | URL | Stack |
|---------|-----|--------|
| Public form | `/quotation`, `/request-quotation` | Blade + Alpine (`quotationWizard`) |
| Confirmation | `/quotation/thank-you/{external_id}` | Blade |
| Admin queue | `/admin` → **Quote requests** | Filament v3 |
| Attachment download | `/admin/quotation-requests/attachments/{id}` | Auth + policy |

Legacy **portal quotations** (`quotations` table, login required) remain separate under `/portal/quotations`.

## Data model

```
quotation_requests     ← canonical JSON payload (Section 6 of spec)
quotation_attachments  ← files on local disk (private)
quotation_request_events ← audit trail
```

**Status workflow:** `pending` → `approved` → `sent_to_erp` | `erp_failed` | `rejected` | `spam`

**Public reference:** `external_id` e.g. `WEB-2026-00001` (configurable prefix).

## Request path (public)

1. `StoreQuotationRequest` — validation, honeypot, rate limit (`throttle:quotation-submit`).
2. `QuotationRequestPayloadBuilder` — normalised `payload_json`.
3. `QuotationRequestController@store` — DB transaction, optional uploads, `submitted` event.
4. Redirect to confirmation with `external_id`.

## Admin path

| Action | Class |
|--------|--------|
| List / view / edit notes | `QuotationRequestResource` (Filament) |
| Approve / reject / spam | `QuotationRequestWorkflow` |
| Send to ERP | `QuotationRequestImportService` (stub when ERP off) |
| Audit | `QuotationRequestEventLogger` |

## RBAC

| Permission | Purpose |
|------------|---------|
| `view_any_quotation_request` | List in admin |
| `approve_quotation_request` | Approve pending |
| `reject_quotation_request` | Reject with reason |
| `send_quotation_request_to_erp` | Trigger import |
| `mark_quotation_request_spam` | Mark spam |

Roles: `quotation_admin`, `sales_officer`, `finance_officer`, `super_admin` (all permissions).

## Configuration

`config/quotation_requests.php` — job types, finishing options, upload limits, rate limit, ERP endpoint path.

Environment keys: see `.env.example` (`QUOTATION_*`, `ERP_*`).

## Phase roadmap

| Phase | Status |
|-------|--------|
| 1 — DB, public form, admin queue | **Done** |
| 2 — Email notifications, virus scan | Pending |
| 3 — ERP schema + import API | External (Press ERP) |
| 4 — Wire `QuotationRequestImportService` to HTTP client | Pending |
| 5 — ERP UI banner / webhook | Pending |

## Key files

```
app/Models/QuotationRequest.php
app/Http/Controllers/QuotationRequestController.php
app/Http/Requests/StoreQuotationRequest.php
app/Filament/Resources/QuotationRequestResource.php
app/Services/QuotationRequests/
app/Services/Erp/QuotationRequestImportService.php
config/quotation_requests.php
database/migrations/2026_06_03_100000_create_quotation_request_tables.php
resources/views/pages/quotation.blade.php
resources/js/quotation-wizard.js
```
