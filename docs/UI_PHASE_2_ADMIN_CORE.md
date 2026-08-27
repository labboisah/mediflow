# Phase 2: Admin/Core Modern UI Migration

## Objective

Move the core administration screens into `layouts.modern` first. These screens define users, roles, permissions, modules, services, departments, wards, and other setup data used by the rest of Mediflow.

If the migration resumes later, start from `docs/UI_MODERN_MIGRATION_CONTINUATION_GUIDE.md`.

## Migration Order

1. Superadmin/Admin Dashboard - completed in Phase 1 trial
2. Access Control - completed
3. Users - completed
4. License Management - completed
5. Departments
6. Services
7. Investigations
8. Wards
9. Beds
10. Temporary Permissions
11. System tools

## Completed In This Phase

### Access Control

Files updated:

- `app/Livewire/Admin/AccessControlManager.php`
- `resources/views/components/admin/access-control-manager.blade.php`

Changes:

- Switched from `layouts.live` to `layouts.modern`.
- Added `pageTitle` and `pageSubtitle` for the modern topbar.
- Rebuilt the screen with `x-ui.*` components.
- Preserved all existing Livewire methods and bindings.
- Preserved protected-role behavior for `superadmin` and `administrator`.
- Preserved licensed module access toggles.
- Preserved role permission editing.
- Preserved permission creation.
- Preserved user-to-role assignment.

### Users

Files updated:

- `app/Http/Controllers/Admin/UserController.php`
- `resources/views/admin/users/index.blade.php`
- `resources/views/admin/users/create.blade.php`
- `resources/views/admin/users/edit.blade.php`
- `resources/views/admin/users/show.blade.php`

Changes:

- Switched user index/create/edit/show pages from `layouts.app` to `layouts.modern`.
- Rebuilt filters, tables, profile view, and forms with `x-ui.*` components.
- Preserved search, role filter, deleted-user filter, pagination, create, update, restore, and delete actions.
- Moved department lookup out of Blade views and into the controller.
- Eager-loaded user departments on the index/show paths for cleaner rendering.

### License Management

Files updated:

- `app/Livewire/Admin/LicenseManagement.php`
- `resources/views/components/admin/license-management.blade.php`
- `config/mediflow_modules.php`
- `app/Services/LicenseService.php`
- `app/Models/ClientLicense.php`
- `database/migrations/2026_08_27_000005_add_branch_limit_to_client_licenses_table.php`
- `database/seeders/ModulePermissionSeeder.php`

Changes:

- Added a modern license management screen.
- Added the six license groups: Diagnostic Center, Maternity Clinic, Hospital, General Clinic, Pharmacy, and Enterprise Hospital.
- Treats modules as license features.
- Supports plan presets and custom feature selection.
- Adds `branch_limit` for Enterprise Hospital licenses.
- Adds visible sidebar entry for License Management.

## Verification

Run after each Admin/Core screen migration:

```bash
php -l app/Livewire/Admin/ChangedComponent.php
php artisan view:cache
php artisan route:list --name=admin.target-route --except-vendor
php artisan view:clear
```

## Notes

The current environment still needs Node/NPM available on PATH to run the real Tailwind build. Until then, `layouts.modern` uses `public/css/modern-fallback.css`.
