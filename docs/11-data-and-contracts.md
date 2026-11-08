# Data model and proposed contracts

These are design sketches, not implemented endpoints or final migration definitions. API names and payloads require review with MediFlow, MedPass and KernelBridge owners. Clinical content and secrets are intentionally absent from examples.

## Core entities

| Entity | Important fields / constraints |
|---|---|
| Organization | UUID, legal identity reference, status; tenant boundary. |
| Facility | UUID, organization UUID, status, locale/time zone; belongs to one organization. |
| Membership | User UUID, organization/facility scope, role, status; unique active assignment per policy. |
| ServerInstallation | UUID, facility, stable local identity, version, status, enrolled/last-seen; unique identity, credential lifecycle. |
| LicensingLink | Tenant, KernelBridge account-link reference, product, last verified status/expiry/revision. Do not store plaintext credentials. |
| SyncEvent | Tenant, installation, event UUID, stream/sequence, type/schema, hash, status and timestamps; unique `(installation_id, event_id)` and sequence constraint per stream. |
| SyncAttempt | Event/batch, attempt number, outcome, correlation ID and next retry; no patient content in error messages. |
| PatientLink | Scoped pseudonymous references, verification method, reviewer, status, expiry, version; pending ambiguity is not a match. |
| SharingGrant | Link, recipient, purpose, resource scope, legal basis/evidence, effective/expiry, state, revision and revocation acknowledgements. |
| ExchangeItem | Link, source/recipient, source resource reference/version, state, grant revision, provenance, source update/transfer timestamps, protected payload reference. |
| BackupSet | Facility/installation, encrypted object manifest, integrity, consistency time, retention/hold, status and restore-test history. |
| Provider/Agreement | Provider identity, verifier, relationship, scoped services, status, effective/expiry and agreement version. |
| AuditEvent | Tenant, actor, action, subject reference, purpose, result, timestamp and correlation ID; tamper-resistant retention. |

Every tenant-owned table includes tenant scope, appropriate foreign keys, indexes and retention rules. Use database-enforced uniqueness and transactional updates for authorization-sensitive state changes. Never use a cross-tenant global identifier alone as authorization. Store clinical payloads separately from operational metadata under explicit retention and access rules.

## Sync envelope example

```json
{
  "schema_version": 1,
  "event_id": "00000000-0000-4000-8000-000000000001",
  "installation_id": "00000000-0000-4000-8000-000000000002",
  "stream": "operational",
  "sequence": 42,
  "event_type": "server.health.observed",
  "occurred_at": "2030-01-01T10:00:00Z",
  "payload_sha256": "<hash-of-canonical-payload>",
  "payload": {"pending_count": 0}
}
```

Define canonicalization and maximum bytes before implementation; do not treat a hash as authentication. Tenant comes from authenticated installation binding, not a trusted client-supplied tenant value. An acknowledgement includes event ID, accepted/duplicate/rejected status, stable error code and correlation ID. The server persists dedupe and accepted effect atomically. Duplicate ID with another hash is an error, never a successful duplicate.

## Proposed API surfaces

| Surface | Examples | Authentication / constraints |
|---|---|---|
| Portal API | Organization, facility, server status, backup status for the React portal. | Same-origin session + MFA + CSRF, server-enforced role and tenant scope; versioned responses and typed frontend contract. |
| Connector API | Enrollment proof, heartbeat, batch upload, command polling/acknowledgement. | Installation credential, TLS, bound tenant/facility, rate limits and replay controls. |
| Exchange API | Patient link, grant status, scoped request, delivery receipt. | Approved partner authentication and purpose-specific authorization; versioned clinical contract. |
| Licensing integration | Account link/status and installation activation via supported KernelBridge API/SDK. | Server-side product credential and separate installation credential; verify signatures. |
| Backup API | Initiate upload, chunk authorization, finalize, restore request. | Dedicated scoped credentials, integrity checks, policy and privileged restore approvals. |

Use explicit versioning, bounded pagination, request IDs, stable error codes, idempotency and contract tests. Audit authorization failures. Never put tokens, patient data or secrets in URLs, operational logs or example fixtures.

## Transaction and concurrency cases

- Concurrent enrollment/replay: single-use challenge consumed atomically and bound to expected facility.
- Revocation versus delivery: atomically compare current grant revision at dispatch; a remote receipt may still arrive after revocation, so retain accurate history.
- Duplicate/out-of-order updates: unique event/sequence and source version; hold gaps, no clinical last-write-wins.
- Plan limit: check and reserve usage atomically at the product-owned operation boundary; reconcile with KernelBridge's entitlement snapshot, not UI labels.
- Deletion: explicitly model versioned withdrawal/tombstone and retention; never silently purge clinical records or audit history.
