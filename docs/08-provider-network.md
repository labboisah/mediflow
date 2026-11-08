# Provider network and online healthcare services

## Purpose and scope

Provide a verified, opt-in directory and controlled relationships between healthcare organizations. Start with administrative onboarding and service discovery; patient referrals or clinical exchange are a separate phase requiring approved contracts, consent/legal basis, identity matching and security controls.

MediFlow already documents a local Care Network with independent approval and selected disclosures. It operates within a single installation and does not establish a global federated directory. Reuse its concepts only after verifying implementation, deployment and product boundaries; do not assume the hosted platform already interoperates with it.

## Lifecycle

`prospect -> invited -> submitted -> under_review -> approved -> agreement_pending -> active -> suspended/expired/revoked`

- Store provider organization identity separately from a relationship, facility, service, and user membership.
- Verify legal identity, professional credentials and relevant regulatory details through approved human processes; retain evidence privately and subject to retention rules.
- A reviewer and an independent approver record decisions. Approval alone is not activation: an authorized representative accepts an agreement and the operator explicitly activates the relationship.
- Scope agreements by services, locations, purpose, validity and allowed interactions. Recheck status for each action; suspension blocks new access while preserving required audit records.
- Limit directory visibility and avoid treating self-declared availability as verified clinical capacity.

## Future clinical workflow

An authorized clinician selects a provider and scope, previews data, records the appropriate authority, and confirms a referral. A case has its own lifecycle, recipient assignment, selected disclosure and immutable history. Acceptance, report delivery, amendment, local review, cancellation and revocation are explicit. Already downloaded data cannot be recalled. The receiving provider remains responsible for its own clinical decisions.

## Pilot gates

No public partner search, clinical sharing, external document upload or telehealth launch until provider verification, contracts, malware handling, audit, recipient authorization, incident response and end-to-end testing are approved. Do not equate subscription access with permission to view patient data.
