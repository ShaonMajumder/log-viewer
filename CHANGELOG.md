# Changelog

## v0.0.19 - 2026-05-09
### Added
- Session-based fallback user resolution for custom login flows (`user_id`, `auth_user_id`, `login_user_id`, `id`).

### Changed
- Default middleware set to `['web']` while keeping `auth_required => true`.
- Login enforcement now relies on package access checks to avoid redirect loops in apps with non-standard auth middleware behavior.

### Fixed
- Prevented false 403 for logged-in users in custom session-auth applications.

## v0.0.18 - 2026-05-09
### Added
- N/A

### Changed
- Default access restored to login-required while allowing all authenticated users:
  - `middleware` => `['web', 'auth']`
  - `auth_required` => `true`
  - `authorize` callback returns `(bool) $user`

### Fixed
- Aligns default behavior with expected “logged-in users can view” policy.
