# AGENTS.md

## Purpose
This file defines guardrails for human and AI contributors working on `log-viewer`.
The goal is to keep changes secure, stable, and maintainable across all development.

## Core Principles
- Security first: never trade security for convenience.
- Backward compatibility: avoid breaking existing integrations.
- Small safe changes: prefer minimal, testable diffs.
- Clear ownership: every change must be understandable by the next maintainer.

## Mandatory Guardrails

### 1) Security
- Never expose log viewer routes publicly without middleware/auth.
- Keep authorization checks in place (`config/log-viewer.php` authorize callback).
- Block path traversal and arbitrary file reads in all file access code.
- Do not leak sensitive data in error messages, responses, or UI.
- Validate and sanitize all request inputs (search, filters, limits, file names).

### 2) Routing and Access Control
- Route changes must preserve existing defaults unless versioned.
- Any new endpoint must follow the same middleware + authorization model.
- Download/export endpoints must enforce the same auth rules as view endpoints.

### 3) Performance and Reliability
- Avoid loading very large logs fully into memory when not required.
- Keep filtering and tailing logic efficient and bounded by user-configurable limits.
- Add sensible defaults and hard caps for heavy operations.

### 4) UI/UX Standards
- Keep readability as top priority for log analysis.
- Maintain theme consistency; if adding themes, provide at least:
  - `dark`
  - `light`
  - one additional accessible theme
- Ensure contrast and level highlighting remain accessible in every theme.

### 5) Code Quality
- Follow PSR standards and existing package style.
- Prefer focused methods over large monolithic functions.
- Document non-obvious logic with concise comments.
- Avoid introducing new dependencies unless clearly justified.

### 6) Testing Requirements
- Every behavior change should include or update tests where possible.
- At minimum, validate:
  - authorization behavior
  - file discovery safety
  - filtering/tail/context behavior
  - download behavior
- Run package tests before release.

### 7) Config and Backward Compatibility
- New config keys must include safe defaults.
- Do not rename/remove config keys without migration notes.
- Keep published config and README in sync with code.

### 8) Documentation
- Update `README.md` for any user-visible behavior/config/routing changes.
- Include upgrade notes for breaking changes.
- Keep examples copy-paste ready.
- Every new feature must include a dated document under `docs/<YYYY-MM-DD>/`.

## Git Hygiene
- Keep branches focused on one feature/fix whenever possible.
- Use clear commit messages that explain intent, not just file changes.
- Do not mix unrelated refactors with feature delivery in the same commit.
- Every new feature must ship with supporting docs in `docs/<YYYY-MM-DD>/`.
- The dated doc should include:
  - summary of the feature
  - files changed
  - config/migration impact
  - test coverage notes

## Version Naming and Management
- Use semantic versioning format: `MAJOR.MINOR.PATCH` (example: `1.4.2`).
- Version pattern must always be numeric as `x.y.z` (`_._._` equivalent).
- Increment `PATCH` for bug fixes and internal improvements with no breaking change.
- Increment `MINOR` for backward-compatible features.
- Increment `MAJOR` for breaking changes in API, behavior, or config expectations.
- Do not release breaking changes without explicit migration notes.
- Keep version updates synchronized across release notes, tags, and documentation.
- Each release must include a short changelog summary:
  - Added
  - Changed
  - Fixed

## Pull Request Checklist
Before merging, confirm:
- [ ] Security checks preserved or improved.
- [ ] Auth/middleware behavior unchanged or intentionally documented.
- [ ] No sensitive data exposure added.
- [ ] Performance impact considered for large logs.
- [ ] Tests added/updated for changed behavior.
- [ ] README/config docs updated.
- [ ] No accidental breaking changes.

## Agent Operating Rules
When an AI agent contributes:
- Make the smallest safe change that solves the task.
- Explain assumptions explicitly in commit/PR notes.
- If a task conflicts with security guardrails, stop and request human approval.
- Never auto-remove protections to make tests pass.

## Escalation Rules
Pause and escalate to maintainer when:
- A change may weaken auth/security.
- A change introduces breaking API/config behavior.
- A fix requires significant performance tradeoffs.
- There is uncertainty about legal/compliance impact of log handling.

## Definition of Done
A change is done only when:
- Code is secure and readable.
- Behavior is validated.
- Docs are updated.
- Guardrails in this file remain satisfied.
