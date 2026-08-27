# Modern UI Migration Continuation Guide

## Purpose

Use this document as the prompt guide for continuing the Mediflow modern UI migration later. It records where the work stopped, what has already been completed, and the exact order to follow until the full system is migrated.

## Current Stop Point

Migration paused after starting Phase 2.

Completed modern UI areas:

1. Modern UI foundation
2. Superadmin/Admin dashboard
3. Access Control
4. Admin Users

Do not restart these from scratch unless a bug is found. Continue from **Departments**.

## Foundation Already Created

Modern layout and shell:

- `resources/views/layouts/modern.blade.php`
- `resources/views/layouts/partials/modern-sidebar.blade.php`
- `resources/views/layouts/partials/modern-topbar.blade.php`
- `resources/views/layouts/partials/modern-alerts.blade.php`

Modern assets:

- `resources/css/modern.css`
- `resources/js/modern.js`
- `public/css/modern-fallback.css`
- `tailwind.config.js`
- `postcss.config.js`

Reusable components:

- `resources/views/components/ui/page.blade.php`
- `resources/views/components/ui/card.blade.php`
- `resources/views/components/ui/button.blade.php`
- `resources/views/components/ui/input.blade.php`
- `resources/views/components/ui/select.blade.php`
- `resources/views/components/ui/textarea.blade.php`
- `resources/views/components/ui/badge.blade.php`
- `resources/views/components/ui/table.blade.php`
- `resources/views/components/ui/empty-state.blade.php`

Reference docs:

- `docs/UI_ARCHITECTURE.md`
- `docs/UI_PHASE_1_FOUNDATION.md`
- `docs/UI_PHASE_2_ADMIN_CORE.md`
- `docs/SIDEBAR_GROUPING_IMPLEMENTATION.md`
- `docs/RBAC_MODULE_ACCESS_IMPLEMENTATION.md`
- `docs/MODULE_LICENSING_APPROACH.md`

## Screens Already Migrated

### Superadmin/Admin Dashboard

Files:

- `app/Livewire/Admin/Dashboard.php`
- `resources/views/components/admin/dashboard.blade.php`

Status:

- Uses `layouts.modern`.
- Uses modern dashboard card layout.
- Uses `pageTitle` and `pageSubtitle`.
- Sidebar collapse/scroll behavior is tested from this page.

### Access Control

Files:

- `app/Livewire/Admin/AccessControlManager.php`
- `resources/views/components/admin/access-control-manager.blade.php`

Status:

- Uses `layouts.modern`.
- Preserves role editing.
- Preserves permission creation.
- Preserves role-user assignment.
- Preserves user-module access toggles.
- Preserves protected `superadmin` and `administrator` role behavior.

### Admin Users

Files:

- `app/Http/Controllers/Admin/UserController.php`
- `resources/views/admin/users/index.blade.php`
- `resources/views/admin/users/create.blade.php`
- `resources/views/admin/users/edit.blade.php`
- `resources/views/admin/users/show.blade.php`

Status:

- Uses `layouts.modern`.
- User list, filters, create, edit, and show pages migrated.
- Department lookup moved from Blade to controller.
- User index eager-loads roles and departments.

## Important Technical Notes

### Asset Build

Node/NPM was not available in the current shell, so `npm run build` could not be verified.

Until the real Vite build is generated, `layouts.modern` uses:

- `public/css/modern-fallback.css`
- existing built `resources/js/app.js`

When Node/NPM is available, run:

```bash
npm run build
```

Then confirm `public/build/manifest.json` contains:

- `resources/css/modern.css`
- `resources/js/modern.js`

### Layout Rule

Only migrated screens should use:

```blade
@extends('layouts.modern')
```

or:

```php
#[Layout('layouts.modern')]
```

Unmigrated screens should remain on:

- `layouts.app`
- `layouts.live`

### Livewire Metadata Rule

Every migrated Livewire component should pass:

```php
'pageTitle' => 'Screen Name',
'pageSubtitle' => 'Short screen purpose.',
```

The view should use:

```blade
<x-ui.page :title="$pageTitle" :subtitle="$pageSubtitle">
    ...
</x-ui.page>
```

### Controller View Metadata Rule

Every migrated Blade controller view should include:

```blade
@section('title', 'Screen Name')
@section('page-title', 'Screen Name')
@section('page-subtitle', 'Short screen purpose.')
```

and wrap the content with:

```blade
<x-ui.page title="Screen Name" subtitle="Short screen purpose.">
    ...
</x-ui.page>
```

## Continue From Here

Start with **Departments**.

Recommended next prompt:

```text
Continue the modern UI migration from docs/UI_MODERN_MIGRATION_CONTINUATION_GUIDE.md. Start with Admin Departments. Inspect the controller, routes, and views first. Migrate only the Department admin screens to layouts.modern using x-ui components. Preserve all current behavior and run verification.
```

## Remaining Phase 2: Admin/Core

Continue in this exact order:

1. Departments
2. Services
3. Investigations
4. Wards
5. Beds
6. Temporary Permissions
7. System Sync Dashboard
8. System Update
9. Backup

### Prompt For Departments

```text
Continue Phase 2 modern UI migration. Migrate Admin Departments to layouts.modern. Inspect app/Http/Controllers/Admin/DepartmentController.php and resources/views/admin/departments/*.blade.php. Preserve create, edit, delete, validation, redirects, and permissions. Use x-ui.page, x-ui.card, x-ui.table, x-ui.input, x-ui.button, and x-ui.empty-state. Move direct model queries out of Blade if found. Run php lint, php artisan view:cache, route:list for admin.departments, and view:clear.
```

### Prompt For Services

```text
Continue Phase 2 modern UI migration. Migrate Admin Services to layouts.modern. Inspect the service controller, routes, and resources/views/admin/services/*.blade.php. Preserve service creation, editing, restore/delete, pricing, category or department relationships, and validation. Use the existing modern UI components. Avoid business logic refactors unless required. Verify with php lint, view:cache, route:list for admin.services, and view:clear.
```

### Prompt For Investigations

```text
Continue Phase 2 modern UI migration. Migrate Admin Investigations to layouts.modern. Inspect Admin/InvestigationController and resources/views/admin/investigations/*.blade.php. Preserve investigation CRUD, type/department relationships, parameters if present, validation, and permissions. Use x-ui components and keep tables readable. Verify with php lint, view:cache, route:list for admin.investigations, and view:clear.
```

### Prompt For Wards And Beds

```text
Continue Phase 2 modern UI migration. Migrate Admin Wards and Beds to layouts.modern. Inspect WardController, BedController, and resources/views/admin/wards/**/*.blade.php. Preserve ward CRUD, bed CRUD, ward-scoped bed routes, and route parameters. Be careful not to create sidebar links for parameterized bed routes. Verify route generation, php lint, view:cache, route:list for admin.wards/admin.beds, and view:clear.
```

### Prompt For Temporary Permissions

```text
Continue Phase 2 modern UI migration. Migrate Admin Temporary Permissions to layouts.modern. Inspect TemporaryPermissionController and resources/views/admin/temporary-permissions/*.blade.php. Preserve temporary grant creation, revocation, expiry display, user/permission relationships, and audit behavior. Use modern form and table components. Verify with php lint, view:cache, route:list for admin.temporary-permissions, and view:clear.
```

### Prompt For System Tools

```text
Continue Phase 2 modern UI migration. Migrate system admin screens to layouts.modern: Sync Dashboard, System Update, and Backup. Inspect related controllers/Livewire components and views first. Preserve all operational behavior. Keep dangerous actions clearly separated and confirmable. Use x-ui components and readable status panels. Verify each route with php lint where applicable, view:cache, route:list for admin.sync/admin.system/admin.backup, and view:clear.
```

## Phase 3: Patient And Front Desk

Start only after Phase 2 is complete.

Order:

1. Record Officer Dashboard
2. Patient List
3. Patient Registration
4. Patient Search
5. Patient Show/Profile
6. Patient Edit
7. Patient History
8. Visits
9. Appointments
10. Referrals

Prompt:

```text
Start Phase 3 modern UI migration from docs/UI_MODERN_MIGRATION_CONTINUATION_GUIDE.md. Begin with Record Officer and Patient list screens. Preserve patient registration/search/visit behavior. Use layouts.modern and x-ui components. Keep forms highly readable and avoid backend refactors unless needed.
```

## Phase 4: Clinical Workflows

Order:

1. Nurse patient workspace
2. Doctor patient workspace
3. Vital signs
4. Observations
5. Prescriptions
6. Drug charts
7. Fluid balance
8. Admissions
9. Discharge
10. Continuation sheets

Prompt:

```text
Start Phase 4 modern UI migration. Migrate clinical workflows module by module using layouts.modern. Preserve all patient context, route parameters, Livewire state, validation, and clinical record relationships. Do not change medical workflow logic unless required for UI compatibility.
```

## Phase 5: Pharmacy

Order:

1. Prescriptions queue
2. Dispensing workspace
3. Medicines
4. Stock
5. Batches
6. Expiry alerts
7. Transactions
8. Stock reconciliation
9. Pharmacy finance

Prompt:

```text
Start Phase 5 modern UI migration. Migrate Pharmacy screens to layouts.modern using x-ui components. Preserve dispensing, stock movement, batches, expiry alerts, transactions, reconciliation, and pharmacy finance behavior. Keep inventory tables dense but readable.
```

## Phase 6: Diagnostics

Order:

1. Lab requests
2. Lab result entry
3. Lab investigations
4. Lab parameters
5. Radiology requests
6. Radiology result entry
7. Radiology investigations
8. Radiology parameters

Prompt:

```text
Start Phase 6 modern UI migration. Migrate Laboratory and Radiology screens to layouts.modern. Preserve request queues, result entry, investigation setup, parameters, file/image result handling, and permissions. Keep lab/radiology layouts consistent.
```

## Phase 7: Maternity

Order:

1. Midwife dashboard
2. ANC patients
3. Antenatal care
4. Labour
5. Labour progress
6. Delivery
7. Newborn
8. Newborn examination
9. Postnatal examination
10. Child follow-up

Prompt:

```text
Start Phase 7 modern UI migration. Migrate Maternity screens to layouts.modern. Preserve antenatal, labour, delivery, newborn, postnatal, and child follow-up workflows. Keep forms sectioned, readable, and faithful to existing validation and route parameters.
```

## Phase 8: Billing, Finance, And Reports

Order:

1. Bills
2. Payments
3. Expenses
4. Revenues
5. Finance reports
6. Payment reports
7. Activity reports
8. Department reports
9. Export/PDF views

Prompt:

```text
Start Phase 8 modern UI migration. Migrate Billing, Finance, and Reports screens to layouts.modern. Preserve calculations, filters, exports, PDFs, receipts, role restrictions, and audit behavior. Keep tables readable and money/status values easy to scan.
```

## Standard Verification For Every Migration Slice

Run:

```bash
php -l path/to/changed/php/file.php
php artisan view:cache
php artisan route:list --name=target.route.prefix --except-vendor
php artisan view:clear
```

If a migrated screen uses a controller view, test:

- index route
- create route
- edit route with an existing record
- show route if available
- POST/PUT/DELETE action paths if safe in the local environment

If a migrated screen uses Livewire, test:

- component mounts successfully
- filters update
- buttons fire the expected Livewire action
- validation errors display correctly
- toast events still display

## Do Not Forget

- Keep old layouts until all screens are migrated.
- Keep module licensing and user module access as the sidebar authority.
- Do not add hardcoded sidebar links.
- Avoid broad backend refactors during UI migration.
- Update this document after each completed module.
- Update `docs/UI_PHASE_2_ADMIN_CORE.md` while Phase 2 is active.
