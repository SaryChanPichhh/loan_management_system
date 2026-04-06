---
name: setecloan_project
description: >
  Full context skill for the SetecLoan Laravel Loan Management System.
  Use this skill whenever working on any part of this project — backend, frontend,
  database, routes, or business logic. It captures architecture, conventions, models,
  loan lifecycle, route structure, view layout, and business rules so you never need
  to re-explore the codebase from scratch.
---

# SetecLoan — Project Skill

## 1. Project Overview

| Property        | Value                                   |
|-----------------|------------------------------------------|
| **App Name**    | SetecLoan                               |
| **Framework**   | Laravel (PHP)                           |
| **DB**          | MySQL — `laravel_loan_management_db`    |
| **DB Host**     | 127.0.0.1:3306 (user: root)             |
| **Dev URL**     | http://127.0.0.1:8000                   |
| **Run server**  | `php artisan serve --port=8000`          |
| **Locale**      | English UI + Khmer labels in models     |
| **Session**     | Database-backed                         |
| **Queue**       | Database                                |

---

## 2. Directory Layout

```
loan_management_system/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── HomeController.php              ← Public homepage "/"
│   │       └── Backend/                        ← All admin controllers
│   │           ├── AuthController.php
│   │           ├── CustomerController.php
│   │           ├── DashboardController.php
│   │           ├── GuarantorController.php
│   │           ├── LoanApplicationController.php
│   │           ├── LoanController.php           ← Largest controller ~35KB
│   │           ├── LoanProductController.php
│   │           ├── RepaymentController.php
│   │           ├── ReportController.php
│   │           ├── NotificationController.php
│   │           ├── RoleController.php
│   │           ├── PermissionController.php
│   │           ├── ProfileController.php
│   │           ├── ActivityLogController.php
│   │           └── SettingController.php
│   └── Models/
│       ├── Customer.php
│       ├── Loan.php
│       ├── LoanApplication.php
│       ├── LoanProduct.php
│       ├── LoanSchedule.php
│       ├── Guarantor.php
│       ├── Repayment.php
│       ├── Notification.php
│       ├── Permission.php
│       ├── Role.php
│       ├── User.php
│       ├── ExchangeRate.php
│       └── ActivityLog.php
├── resources/views/
│   ├── frontend/                   ← PUBLIC portfolio site
│   │   └── home.blade.php          ← Homepage "/"
│   ├── backend/                    ← ADMIN panel views
│   │   ├── layout/                 ← Base admin layout
│   │   ├── auth/                   ← Login, register
│   │   ├── dashboard/
│   │   ├── customer/
│   │   ├── loans/
│   │   ├── loan_applications/
│   │   ├── loan_products/
│   │   ├── guarantors/
│   │   ├── repayments/
│   │   ├── notifications/
│   │   ├── role/
│   │   ├── permission/
│   │   ├── profile/
│   │   ├── report/
│   │   ├── settings/
│   │   └── activity_log/
│   └── home/                       ← Legacy (unused)
├── public/assets/
│   ├── css/
│   │   ├── styles.css              ← Admin panel CSS
│   │   └── frontend.css            ← Public frontend CSS
│   ├── js/
│   │   ├── scripts.js              ← Admin panel JS
│   │   └── frontend.js             ← Public frontend JS
│   └── img/
│       ├── setecloan_hero.png      ← AI-generated hero image
│       └── ...
├── routes/
│   └── web.php                     ← ALL routes in one file
└── database/
    ├── migrations/                 ← 33 migration files
    └── seeders/
```

---

## 3. Route Structure (`routes/web.php`)

### Public Routes (no auth)
```
GET  /                              → HomeController@index        [home]
GET  /admin/v1/login                → AuthController@login        [login.index]
POST /admin/v1/login                → AuthController@store_login  [login.store]
GET  /admin/v1/logup                → AuthController@logup        [logup.index]
POST /admin/v1/logup                → AuthController@store_logup  [logup.store]
POST /admin/v1/logout               → AuthController@logout       [logout]
```

### Protected Admin Routes — Prefix: `/admin/v1/`
All routes use middleware: `['auth', 'permission']`

| Module          | Base Path                    | Key Actions                          |
|-----------------|------------------------------|--------------------------------------|
| Dashboard       | `/admin/v1/dashboard`       | index                                |
| Customers       | `/admin/v1/customer`        | CRUD + show                          |
| Loans           | `/admin/v1/loans`           | CRUD + review, approve, reject, disburse, payments |
| Repayments      | `/admin/v1/repayments`      | index, store, overdue, create, edit  |
| Reports         | `/admin/v1/report`          | index                                |
| Notifications   | `/admin/v1/notification`    | CRUD                                 |
| Roles           | `/admin/v1/roles`           | index, updatePermissions, editUserPermissions |
| Permissions     | `/admin/v1/permissions`     | CRUD                                 |
| Profile         | `/admin/v1/profile`         | edit, update                         |
| Activity Log    | `/admin/v1/activity-log`    | index                                |
| Settings        | `/admin/v1/settings`        | company-profile, exchange-rate CRUD  |
| Loan Products   | `/admin/v1/loan-products`   | CRUD + toggleStatus                  |
| Guarantors      | `/admin/v1/guarantors`      | CRUD                                 |
| Loan Apps       | `/admin/v1/loan-applications` | CRUD + updateStatus                |

---

## 4. Models & Key Fields

### `Customer`
- Fields: `code`, `name`, `gender`, `phone`, `email`, `address`, `national_id`,
  `date_of_birth`, `age_verified`, `occupation`, `monthly_income`,
  `has_existing_loan`, `credit_score`, `type`, `status`, `document_path`, `created_by`
- Uses: `SoftDeletes`
- Relations: `hasMany(Loan)`, `hasMany(Guarantor)`

### `LoanProduct`
- Fields: `product_code`, `name`, `description`, `min_amount`, `max_amount`,
  `interest_rate`, `interest_type` (FLAT | REDUCING_BALANCE | COMPOUND),
  `max_term_months`, `grace_period_days`, `late_fee_rate`,
  `requires_guarantor_above`, `requires_collateral_above`, `penalty_rate`, `status`
- Scope: `scopeActive()` — filters by `status = true`
- Labels method: `interestTypeLabel()` returns Khmer + English string

### `LoanApplication`
- Fields: `application_code`, `customer_id`, `product_id`, `requested_amount`,
  `requested_months`, `purpose`, `status`, `reviewed_by`, `reviewed_at`,
  `rejection_reason`, `loan_id`, `created_by`
- Statuses: `pending` → `under_review` → `approved` / `rejected` / `cancelled`
- Relations: `customer`, `product`, `reviewer`, `creator`, `loan`
- Attribute: `status_badge_html` — returns Bootstrap badge HTML with Khmer labels

### `Loan`
- Fields: `loan_code`, `customer_id`, `application_id`, `product_id`,
  `principal_amount`, `disbursed_amount`, `interest_rate`, `duration_months`,
  `status`, `purpose`, `start_date`, `end_date`, `first_payment_date`,
  `grace_period_end_date`, `early_settlement_date`,
  `collateral_required`, `guarantor_required`,
  `approved_by`, `rejected_by`, `rejected_reason`, `created_by`, `note`
- Uses: `SoftDeletes`
- Statuses: `pending` → `under_review` → `approved` → `active` → `completed` / `defaulted` / `written_off`
- Methods: `statusLabel()` (Khmer), `statusBadge()` (Bootstrap CSS class)
- Relations: `customer`, `product`, `application`, `approvedBy`, `rejectedBy`, `createdBy`, `schedules`, `repayments`

### `Guarantor`
- Linked to a customer, stores guarantor personal info and ID docs

### `LoanSchedule`
- Payment schedule rows per loan. Has unique index `uq_schedule_installment`.

### `Repayment`
- Tracks each payment made against a loan.

---

## 5. Loan Lifecycle (State Machine)

```
LoanApplication:
  (created) → pending
           → [staff review] → under_review
           → [approved] → approved   (loan can now be created)
           → [rejected] → rejected

Loan:
  (created from approved application) → pending
  → [submit for review] → under_review
  → [approve] → approved
  → [disburse] → active     ← schedules generated here
  → [complete] → completed
  → [default] → defaulted
  → [write off] → written_off
```

**Key business rules:**
- Loan is created manually from an approved LoanApplication — not automated.
- Disbursement is a separate step that generates the repayment schedule.
- Guarantor required for loans above $500.
- Collateral required for loans above $5,000 (valued at ≥120% of loan).
- Grace period: 3 days after due date before late fee applies.
- Late fee: 1.5% of outstanding balance per missed payment.
- Early repayment: allowed, no penalty.

---

## 6. Business Rules (SetecLoan)

| Rule                   | Detail                                                  |
|------------------------|---------------------------------------------------------|
| Currency               | USD and KHR (Riel)                                      |
| Personal Loan          | $100 – $5,000                                           |
| Business Loan          | $500 – $20,000                                         |
| Emergency Loan         | $50 – $1,000                                            |
| Salary Advance         | $50 – $2,000 (employees only)                          |
| Rate: 1–3 months       | 3% / month                                              |
| Rate: 4–6 months       | 2.5% / month                                            |
| Rate: 7–12 months      | 2% / month                                              |
| Rate: >12 months       | 1.5% / month                                            |
| Eligibility age        | 18 – 65 years old                                       |
| Late 1–3 days          | Reminder call only, no fee                              |
| Late 4–15 days         | 1.5% late fee on outstanding balance                    |
| Late 16–30 days        | Late fee + formal written warning                       |
| Late >30 days          | Guarantor contacted, legal proceedings begin            |
| Late >60 days          | Collateral claimed (if applicable)                      |
| Collateral threshold   | >$5,000 (must be valued ≥120% of loan)                 |
| Guarantor threshold    | >$500                                                   |
| Customer contact hours | 7am – 8pm only                                          |
| Data retention         | 5 years then securely deleted                           |

---

## 7. Frontend Portfolio (Public Site)

- **Route:** `GET /` → `HomeController@index` → `frontend.home`
- **View:** `resources/views/frontend/home.blade.php`
- **CSS:** `public/assets/css/frontend.css` (~600 lines, CSS variables + design system)
- **JS:** `public/assets/js/frontend.js` (scroll effects, particles, counters, hamburger)
- **Hero image:** `public/assets/img/setecloan_hero.png`

### Design System (frontend.css)
```css
--primary:       #1a3a6b   /* Navy Blue */
--primary-dark:  #0f2347
--primary-light: #2e5ba8
--accent:        #d4a843   /* Gold */
--accent-light:  #f0c46a
--accent-dark:   #b8872a
```
- Fonts: `Inter` (UI) + `Playfair Display` (headings) via Google Fonts
- All section IDs: `#hero`, `#products`, `#rates`, `#eligibility`, `#process`, `#policies`, `#why-us`, `#contact`
- Animations: `.sl-fade-up`, `.sl-fade-in` via IntersectionObserver
- Counter elements use `data-target`, `data-suffix`, `data-prefix` attributes
- All interactive elements have unique descriptive IDs (e.g. `nav-products`, `hero-apply-btn`, `step-1`)

---

## 8. Admin Panel Conventions

- **Namespace:** `App\Http\Controllers\Backend\*`
- **Layout:** `resources/views/backend/layout/` (blade layout files)
- **Auth:** Custom `AuthController` — not Laravel Breeze/Jetstream
- **Middleware:** `auth` + custom `permission` middleware for route-level RBAC
- **RBAC:** `Role` and `Permission` models with pivot table `role_permission_pivots`
- **Activity Logging:** `ActivityLog` model — automatically logs key actions
- **Notifications:** `Notification` model linked to customers
- **Settings:** Exchange rate management + company profile

---

## 9. Database

- **Connection:** MySQL
- **Database:** `laravel_loan_management_db`
- **33 migration files** — always use `php artisan migrate:fresh --seed` to reset, or `php artisan migrate` for incremental
- **Important:** Migration `2026_03_29_062836_update_loan_schedules_table_v2.php` has a unique index `uq_schedule_installment` on `loan_schedules` — do not drop it without handling `down()` carefully
- **Seeders:** Located in `database/seeders/` — preference for Khmer language seed data, minimum 5 records per table

---

## 10. Common Commands

```bash
# Start local dev server
php artisan serve --port=8000

# Run migrations
php artisan migrate

# Fresh migration with seed
php artisan migrate:fresh --seed

# List all routes
php artisan route:list

# Filter routes by name
php artisan route:list --name=loans

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Create a new controller
php artisan make:controller Backend/MyController

# Create a new model with migration
php artisan make:model MyModel -m
```

---

## 11. Known Gotchas & Patterns

1. **Route naming conflict:** `/admin/v1/dashboard` and `/admin/v1/` both map to `dashboard.index` — only keep one or use `->name()` carefully.
2. **RepaymentController filename:** File is `RePaymentController.php` (capital P) but class is `RepaymentController`. Keep consistent.
3. **Loan Products / Guarantors / LoanApplications** routes are **outside** the auth middleware group — verify if that's intentional before editing.
4. **SoftDeletes** on `Customer` and `Loan` — always use `withTrashed()` or `onlyTrashed()` when querying deleted records.
5. **Khmer labels:** Model helper methods like `statusLabel()` and `interestTypeLabel()` return Khmer text — these are for the admin UI display only.
6. **Frontend CSS class prefix:** All frontend-specific classes are prefixed `sl-` to avoid collision with admin styles.
7. **Exchange rate:** Stored in `exchange_rates` table — used when accepting KHR payments vs USD principal amounts.
8. **Disbursement step:** Calling `disburse` on a loan is what generates **LoanSchedule** rows — do not create schedules before this step.
9. **LoanApplication → Loan transition:** Must be manual (staff presses a button to create loan from approved application). Not automated.
10. **Assets path:** Use `asset('assets/css/...')` in Blade — assets live in `public/assets/`, NOT in `resources/`.

---

## 12. Adding a New Feature — Checklist

When adding a new module (e.g., CollateralManager):
- [ ] Create migration: `php artisan make:migration create_X_table`
- [ ] Create model: `php artisan make:model X`
- [ ] Create controller: `php artisan make:controller Backend/XController`
- [ ] Register routes in `routes/web.php` inside the auth middleware group under `/admin/v1/`
- [ ] Create views under `resources/views/backend/x/` (index, create, edit, show)
- [ ] Add sidebar link in `resources/views/backend/layout/`
- [ ] Add permission record in DB if using RBAC
- [ ] Add seeder if needed in `database/seeders/`
