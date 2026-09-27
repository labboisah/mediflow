# Installation Setup

The local installation now has a super-admin-only configuration interface at `/admin/installation`. This implements the local package setup requested in September 2026 and supersedes earlier guidance that all license configuration must be external.

## Use

Sign in with an installation administrator account and open **Installation Setup** in the sidebar. Enter the organization name, choose Pharmacy, General Clinic, Diagnostic Center, Maternity Clinic, Hospital, or Enterprise Hospital, then review the modules and save. Choosing a package resets the draft module selection to that preset; it does not save until Save installation is clicked.

Core and Access Control are required. Other modules can be enabled as add-ons or disabled. Dependencies are validated before saving. Branch Management is reserved for Enterprise. These settings enable existing application capabilities; they do not install separate software or implement additional branch workflows.

Saving updates the latest installation license and records an explicit enabled/disabled row for each known module inside a transaction. Existing operational records, staff roles, and module assignments are retained. Disabled features are hidden in navigation and blocked on direct requests, including persistent Livewire requests. Configured installations use a dashboard listing available workspaces. Activity report screens and exports filter their datasets by enabled modules.

Inactive, future-dated, or expired saved licenses leave only Core and Access Control enabled, while the setup screen remains accessible to installation administrators. An expired license cannot fall back to a previous license or the default hospital package. Before a license is configured, the configured default plan is used for compatibility.

## Administration

The installation-administrator flag is separate from editable staff roles and is not mass assignable. Ordinary administrators cannot modify an installation administrator through User Management. Grant/revoke this flag for an existing account from the server:

```sh
php artisan installation:admin someone@example.com
php artisan installation:admin someone@example.com --revoke
```

Apply the additive `2026_09_27_000001_add_installation_admin_to_users` migration on other installations. No default credentials are seeded. Role permissions and staff module access remain separate from installation configuration.

Package definitions/dependencies: `config/mediflow_modules.php`.
Route restrictions: `config/installation_routes.php`; all matching rules apply and `|` means either module can support a shared route. Add rules when introducing new feature routes. Package checks do not grant operational role permissions and do not constitute cryptographically signed remote licensing.

## Verification

```sh
php vendor/phpunit/phpunit/phpunit tests/Feature/InstallationSetupTest.php
php artisan view:cache
```

Tests use an isolated in-memory SQLite schema and cover setup authorization, revoked access, presets, dependency validation, explicit disabled modules, inactive/expired licenses, direct route restrictions, middleware persistence, and report datasets.
