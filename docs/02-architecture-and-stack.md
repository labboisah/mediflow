# Architecture and technology stack

## Logical architecture

```text
Organization admin browser
        | HTTPS / MFA
        v
React + TypeScript portal
        | same-origin HTTPS / session + CSRF
        v
MediFlow Platform (Laravel API)
  identity / organizations / facilities / servers / audit
  licensing projection / operational sync / exchange / backup control
        |                   |                    |
        | signed status     | authorized data    | encrypted objects
        v                   v                    v
   KernelBridge          MedPass          Private backup storage
                            ^
                            |
Facility MediFlow server -> local connector/outbox
 local EHR + R4 adapter    outbound HTTPS when connected
        ^
        | LAN, including during internet outage
Facility staff
```

External FHIR systems connect only through approved, individually tested integrations. Arrows are conceptual, not proof of implemented connectivity. Online platform unavailability must not interrupt local clinical work.

## Deployable components

| Component | Recommended implementation | Ownership and responsibility |
|---|---|---|
| Hosted portal | React and TypeScript, built with Vite and served as static assets. | Accessible administrative UI, routing, scoped views, loading/error states; no clinical or licensing authorization decisions delegated to the browser. |
| Hosted API | Laravel modular monolith and PHP supported by the selected Laravel release. | Platform team: authentication, tenant membership, APIs, status, audit and policy checks. |
| Background processing | Laravel queue workers and scheduler. | Retries, delivery state, alerting, cleanup; isolate clinical, backup and operational jobs. |
| Database | PostgreSQL, encrypted managed storage and backups. | Organization-scoped metadata, grants, audit and sync ledger; use transactions and uniqueness constraints. |
| Queue/cache | Redis with authentication, isolation and persistence appropriate to queue strategy. | Queue coordination and caching; not the authoritative clinical source. |
| Object storage | Private S3-compatible storage, versioning/immutability where supported. | Encrypted staged clinical exchange objects and separately controlled backup ciphertext. |
| Local connector | MediFlow-side Laravel integration or separately deployed service after version assessment. | Durable outbound queue, source identity, authentication, retries, status, local policy checks. |
| FHIR boundary | MediFlow's existing R4 read-only adapter for supported resources; explicit integration adapter for exchange. | Mapping, authorization, validation and provenance; do not bypass local permission checks. |
| License boundary | KernelBridge Laravel SDK and supported platform-account/installation APIs. | Verify trusted signatures, expose effective status, no client-side licensing credential. |
| Observability | Central metrics and structured logs, alerting and protected audit storage. | No PHI, secrets or raw FHIR payloads in operational logs. |

Use a modular monolith first: organization, identity, licensing, installation, sync, exchange, backup, network, audit and notifications modules with explicit service interfaces. Extract services only after measured operational need. The portal and API can be deployed independently with compatible versioned contracts; separate frontends do not automatically improve backend throughput. Use a shared accessible component system and typed API clients generated from or tested against a versioned API contract. Choose routing and server-state libraries during implementation and pin supported versions.

## Browser and API deployment

The recommended initial deployment serves the React portal and reverse-proxies the Laravel API under the same HTTPS origin. Use server-managed sessions with `HttpOnly`, `Secure`, appropriate `SameSite` cookies, CSRF protection for state-changing requests, session rotation after authentication and MFA, inactivity/absolute expiration and logout invalidation. Do not store session tokens, patient data or long-lived credentials in browser local storage. All tenant and clinical authorization is enforced by Laravel on every request.

If frontend and API are hosted on different origins, define and test exact allowed origins, credentialed CORS, cookie behavior and CSRF protections before deployment; never use wildcard origins with credentials. Mobile or external clients need a separately reviewed authentication flow and must not reuse browser session assumptions. React is a hosted administrative interface, not an offline clinical EHR; local care continues in MediFlow independently.

Use pagination and bounded server-side filtering for large lists; cache only data appropriate to its classification, clear sensitive in-memory state on logout and organization change, and avoid shared browser caches for patient content. Start dashboards with bounded polling; introduce authenticated, tenant-scoped server-sent events or WebSockets only for justified use cases, with reconnect, missed-event recovery and fallbacks. Notifications must not bypass access checks or expose clinical payloads.

## Trust and data boundaries

1. Browser-to-Laravel API: session authentication, CSRF, MFA, organization membership and authorization per request; the React application holds no long-lived service credential.
2. Connector-to-platform: separately provisioned per-installation credential, tenant and facility binding, TLS and revocation. Never reuse a KernelBridge product credential as a sync credential.
3. Connector-to-MediFlow FHIR: dedicated least-privilege service principal and resource abilities plus application permissions; no browser exposure of long-lived token.
4. Platform-to-MedPass/EHR: partner-scoped authorization and purpose-specific patient grants, agreed API versions and delivery receipts.
5. Platform-to-KernelBridge: product-scoped credentials only on backend, least privilege and trusted signing keys from a separate provisioning channel.
6. Platform-to-storage: separate access policies and encryption keys for clinical staging and backups.

## Data flows

- **Operations:** local connector sends allowlisted aggregate metrics and heartbeat; platform persists observations and surfaces stale/failed status.
- **Clinical:** an authorized, patient-scoped request selects supported records; connector reads locally, validates and enqueues selected versions; platform enforces current sharing policy and sends to authorized MedPass recipient. Record the source, version and receipt.
- **Licensing:** account linking and installation activation use supported KernelBridge workflows. Server-side SDK verifies signed snapshots. UI shows source and verification age; labels do not authorize actions.
- **Backup:** local agent produces an encrypted, integrity-checked backup; platform authorizes an upload and monitors completion; ciphertext goes to dedicated storage. Restore requires a separate authorized procedure.

## Environments and delivery

Use isolated development, staging and production infrastructure, separate credentials, keys, databases and storage. Synthetic data only in development/staging unless a specifically authorized controlled dataset is approved. Run CI for PHP tests and static checks, TypeScript type-checking, frontend unit/accessibility and browser tests, migrations, frontend/API contract compatibility and dependency/security checks. Deploy version-compatible frontend and API artifacts with migrations and rollback plans; no customer migrations or license mode changes without approval.

Version selections, hosted region, sizing and exact managed services are implementation decisions. Pin supported dependency releases, and do not deploy local path dependencies as production SDK packages.
