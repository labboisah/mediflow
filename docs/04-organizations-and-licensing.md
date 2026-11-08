# Organizations, servers, and KernelBridge licensing

## Tenant and enrollment lifecycle

1. Authorized platform operator verifies an organization and creates an inactive tenant.
2. An expiring invitation is delivered through an approved channel; the invited administrator authenticates, enables MFA and accepts membership.
3. Organization admin creates a facility and begins server enrollment. A short-lived, single-use enrollment challenge is bound to the tenant and facility.
4. Local server administrator confirms enrollment locally. The platform issues a unique revocable sync credential bound to the installation; return the credential only over a secured channel, store in protected local secret storage, never in URLs or logs.
5. Enrollment becomes active only after proof of possession and policy checks. Audit request, approval, activation and subsequent rotation or revocation.
6. Server replacement or facility transfer requires explicit approval, credential revocation, licensing checks and preserved history. Do not silently rebind an old server identity.

Users may have multiple memberships, but every request resolves a selected tenant and facility scope server-side. Separate platform operator, organization admin, facility admin, clinician, records officer, backup operator and support roles. Role membership alone does not grant access to patient data.

## Licensing lifecycle

KernelBridge remains authoritative for plans, effective entitlements, expiration, and installation grants. MediFlow Platform links an organization account to the correct MediFlow product through a supported product-scoped account-link flow. A local server uses an installation-specific activation flow and verified signed entitlement snapshot through the official SDK; the platform must not distribute its long-lived product credential to the server or browser.

1. Admin requests account link; verify product, account identity and authorization.
2. Show plan and limits returned by the authoritative verified subscription, including verification time, expiry and status.
3. Provision a bounded, installation-specific activation code via a supported KernelBridge flow, only if the feature exists and policy allows it.
4. Server exchanges the code using the SDK; binds the credential and signed snapshot to its installation identity.
5. Refresh according to SDK policy; display cached/offline-grace state accurately. Enforce feature keys and limits on trusted backend boundaries, not plan display names.
6. Handle renewal, suspension, revocation, key rotation and failed verification explicitly; audit decisions and avoid leaking credentials.

**Existing MediFlow caveat:** the local licensing pilot documents direct installation activation and says platform-account broker/outbox delivery is out of scope. Platform-linked installation issuance must be designed and tested; do not assume the existing direct flow already implements it. Preserve existing installations and ask for an approved migration/rebinding plan.

## Entitlement examples

Use a reviewed product catalog for stable capability identifiers and separately defined atomic usage enforcement. Possible capabilities: platform dashboard, MedPass exchange, facility registration, backup service and provider network. Candidate limits: active facilities, linked installations, storage bytes and user seats. These are **proposals**, not existing KernelBridge keys. Separate clinical authorization from paid feature entitlement: both must permit a sharing operation.

## Status and safety

Distinguish `active`, `trial`, `grace`, `expired`, `suspended`, `revoked`, `unavailable` and `not_linked`; show last verification and source. An unavailable licensing service is not proof of revocation. Do not use a success-shaped fallback for invalid signatures. Explicitly approve an offline grace policy and safe continuity policy with clinical and legal owners before production.
