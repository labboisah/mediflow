# Implementation work packages and handoffs

This is a planning guide for developers and coding agents. It does not authorize implementation against a production system. Read the decision register first, inspect the current repositories and uncommitted changes, and use supported package releases. Work packages are sequenced by actual prerequisites; a later pilot must not be enabled merely because code compiles.

## WP-0: contracts and governance

**Owners:** product, clinical, privacy, security, MediFlow and MedPass leads.

- Identify MedPass source and operating environments; inventory capabilities and API ownership.
- Approve jurisdiction, data-hosting and key-custody decisions, lawful processing and data-sharing agreements.
- Publish a jointly versioned FHIR/identity/authorization contract and synthetic fixtures; agree clinical resource scope and contraindications.
- Define pilot scale, reliability targets, support contacts, incident ownership and clinical continuity/license-expiry policy.

**Deliverable:** signed decisions and testable contracts. **Gate:** no real patient exchange before approval.

## WP-1: hosted platform foundation

**Prerequisite:** legal/hosting decisions for the intended environment.

- Scaffold the React/TypeScript portal and Laravel API, with supported PHP, Laravel, Node and frontend versions and immutable dependency management. Serve both through a same-origin HTTPS deployment by default.
- Implement server-managed browser sessions, CSRF, MFA and tenant-scoped authorization; establish a versioned API contract and typed frontend client without placing service credentials in the browser.
- Implement organizations, facilities, users, memberships, invitations, MFA, audit and tenant-scoped authorization. Develop modular boundaries and isolate secrets and environment configuration.
- Establish CI, automated tests, separate environments and initial deployment/rollback runbooks.

**Acceptance:** unauthorized tenant/facility reads and writes fail in API, workers and object storage; browser session/CSRF, onboarding, audit, frontend type-check and contract tests pass. No clinical data required.

## WP-2: licensing and installation registration

**Prerequisites:** WP-1 and agreement with KernelBridge owners.

- Inspect the current SDK and MediFlow direct-installation flow; define and implement supported account-link and installation issuance without copying product credentials to the server.
- Build facility enrollment, revocable installation credentials and safe replacement flow. Keep sync credentials separate from licensing credentials.
- Display verified plan, entitlement, status, expiry and snapshot freshness. Apply tested product-owned entitlement/usage enforcement and clinical continuity policy.

**Acceptance:** sandbox account link and installation exchange verified end-to-end; wrong product, signature, tenant, replay, revocation, offline grace and key-rotation tests pass. Existing direct activations remain unchanged without explicit migration.

## WP-3: operational sync and observability

**Prerequisites:** WP-1, WP-2 and connector ownership/version agreement.

- Build local outbox and bounded outbound transport with atomic dedupe, retry, acknowledgement and reconciliation.
- Ingest approved aggregate metrics and implement tenant-scoped dashboard, alerts and dead-letter workflow.
- Evaluate existing MediFlow sync code and reuse only components that pass the new reliability, privacy and security requirements; never globally enable model sync as a shortcut.

**Acceptance:** simulated offline operation, restart, duplicate, partial batch, ordering gap, queue-full and credential revocation tests show no lost committed events or cross-tenant access.

## WP-4: patient linking and MedPass exchange

**Prerequisites:** WP-0 and WP-3; verified MedPass contract and clinician-approved mappings.

- Implement identity verification, manual ambiguity review, scoped grants, and facility policy decisions.
- Integrate only supported local read-only FHIR operations, validate profiles, carry provenance and freshness, and handle per-record delivery receipts and corrections.
- Recheck grants during selection and delivery, support revocation and offline propagation state, and audit disclosure without logging clinical payloads.

**Acceptance:** synthetic end-to-end tests across an actual MediFlow test server and MedPass test environment; clinical sign-off for mapping and workflows; uncertainty and stale status visible; no automatic patient merges or unsolicited clinical writes.

## WP-5: backup and recovery

**Prerequisites:** approved backup policy, key custody and WP-2 installation identity.

- Build consistent local snapshots, locally encrypted upload, integrity-checked manifest, tenant-isolated storage and status reporting.
- Implement separate recovery authorization, retention and restore procedure.

**Acceptance:** prove an isolated restore of database and required private files, including application-version compatibility; test lost key, tampering, interrupted upload and offline recovery.

## WP-6: provider network and external EHR

**Prerequisites:** approved provider policies; WP-4 for clinical exchange.

- Implement provider verification, independently approved agreements and opt-in membership.
- Onboard one external EHR at a time using documented capability, profile, identity and authorization compatibility; pilot clinical referrals separately.

**Acceptance:** provider status and authorization rechecked on every transaction; security and partner-specific interoperability tests pass; no blanket interoperability claim.

## Handoff checklist

Each package must include the exact versions and environment tested, configuration and deployment steps, migration and rollback impact, operational ownership, test results, open risks, and links to the approved decisions. Explicitly identify what was **not** implemented and whether any action changes patient data, consumes a license slot, transfers sensitive data or modifies a live system. Never include passwords, tokens, private keys or real patient records in handoff documents.
