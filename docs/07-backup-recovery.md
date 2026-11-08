# Backup and disaster recovery

## Scope and separation

Backups are for facility recovery, not a general clinical data-sharing channel. Separate backup metadata, credentials, encrypted objects, keys and authorization from sync staging and MedPass. Confirm ownership, retention, permitted storage locations, key custody, deletion and regulatory obligations in the customer agreement before enabling.

## Backup lifecycle

1. Facility administrator authorizes a backup policy: eligible data, frequency, retention, bandwidth window, storage quota and recovery contacts. Include database, private documents and required configuration while excluding unnecessary caches and secrets according to a documented inventory.
2. Use application-consistent database snapshots and corresponding file snapshots; document version, dependencies and consistency point. Do not copy a live database without a verified consistency mechanism.
3. Encrypt locally before transmission using a per-facility data encryption key, wrapped under an approved key-management design. Transport uses TLS. The exact KMS, key custody, access model and recovery process are open decisions; do not promise zero-knowledge recovery without a working key design.
4. Chunk uploads with content hashes, resumable transfer and server-side validation. Record encrypted manifest, version, byte count and integrity results. Mark complete only after the object set and manifest are durably present and validated.
5. Keep local protected copies when offline; upload when connectivity returns. Alert on stale backup, disk/quota exhaustion and key failures. Apply per-tenant retention, legal holds and audited deletion.
6. Protect at least one separate offline or immutable copy where feasible; separate backup administration from ordinary platform administration.

## Restore and validation

Restore is privileged and separate from ordinary viewing: require an approved recovery request, strong authentication, facility authorization and dual approval where appropriate. Restore into an isolated environment, obtain keys through the approved recovery process, decrypt and verify integrity, restore the compatible application/database/files, and run application and clinical smoke tests before cutover. Record recovery point, duration, outcomes, missing data and approvers.

Run scheduled restore drills, at least at a pilot-defined cadence. Test loss of platform, facility server, network and a key custodian; document key rotation, lost-key consequences, ransomware isolation and offline recovery. A successful upload is not a verified restore. Present RPO/RTO as measured per facility rather than assumed.

## Initial acceptance

- Interrupted upload resumes without falsely marking incomplete data complete.
- Changed/corrupt chunks and wrong key are rejected.
- Cross-tenant object access, unauthorized restore and retention tampering are denied and audited.
- An isolated restore of synthetic records and private files succeeds with tested application version.
- Offline backlog, storage full, expired policy and key-unavailable errors alert the facility and operator.
