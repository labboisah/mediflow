# Offline sync and operational monitoring

## Principles

Facility care continues when the platform or internet is unavailable. The connector initiates outbound connections only and never blocks the clinical transaction on a network call. The platform is not an authoritative replacement for the facility's clinical database. The legacy MediFlow `/api/v1/sync` mechanism is separate; its presence or documentation is not evidence that it is secure, complete or appropriate for clinical exchange. Review the deployed code, enabled models, access controls and migrations before reusing any part.

## Durable outbound design

1. A local transaction records an allowlisted change or a reference to a locally authorized extraction in a durable outbox. Write the outbox entry atomically with the source change when the workflow permits; otherwise use a tested reconciliation process so events cannot be silently missed.
2. Each event has stable `event_id`, installation and tenant binding, type/version, per-stream sequence or source version, occurrence time, creation time, data classification and an immutable payload hash. Assign sequence atomically and retain it across restart.
3. Connector batches bounded events over TLS with its own installation credential. It uses exponential backoff with jitter and bounded concurrency; honor rate limits and do not retry permanent validation or authorization failures forever.
4. Platform authenticates and verifies tenant/installation binding, validates limits/schema and persists deduplication marker and event atomically. Same event ID with a different hash is rejected as a security/integrity incident. Acknowledge only after durable commit.
5. Connector marks each item acknowledged individually. Lost responses are safe to retry; a duplicate with the same hash returns the existing result. Acceptance is distinct from downstream delivery.
6. Track pending, transmitting, accepted, rejected, retrying and delivery-complete states with correlation IDs. Dead-lettered items require operator action and an explicit, audited retry.

Maintain ordered streams where ordering matters. Detect gaps, out-of-order arrival, and conflicting versions; quarantine rather than applying unsafe clinical changes. Batch processing must support partial success without discarding failed items. Apply retention, capacity alarms, disk quotas, poison-message handling and reconciliation, including tombstone/amendment handling. Do not silently drop events when storage fills: alert and follow a facility-approved continuity procedure.

## Inbound commands and policy updates

For pilot, use polling with server-authenticated responses and scoped, expiring command IDs. Commands are acknowledgable, idempotent and replay-protected. Permit only reviewed types such as sync configuration, sharing policy refresh and backup request. Never accept arbitrary shell commands or automatically overwrite clinical records. Capture issuance, verification, local execution and outcome separately. A remote action is not complete until local acknowledgement.

## Authorization and revocation when offline

Check sharing authorization at selection, queueing and delivery, not only when the request was first created. Pending clinical data must be quarantined or discarded per approved retention policy if authorization expires or is revoked. For a disconnected facility, revocation cannot reach it immediately; the platform must stop onward deliveries it controls and show propagation as pending until the facility acknowledges the policy. Previously delivered copies cannot be recalled automatically.

## Operational monitoring

Use an allowlist of aggregate, non-identifying metrics: installation version, heartbeat timestamp, queue counts and oldest age, sync success/failure timestamps, backup result and disk capacity. Show timestamps and expected heartbeat interval. Proposed statuses: `online`, `delayed`, `offline`, `unknown`, with thresholds configured by facility and connection profile. Do not expose record contents or identifiers in dashboard logs or alert messages.

## Integration verification

Test outage across process restart and full disk; credential revocation; repeated events and mismatched hashes; race conditions; out-of-order and missing sequence; partial batches; expired grants; retry exhaustion; and reconnect after a prolonged outage. Use synthetic patient data and verify no duplicate clinical delivery or unintended tenant access.
