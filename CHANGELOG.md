# Changelog

## v0.0.20 - 2026-05-09
### Added
- Extended custom session auth compatibility:
  - supports session payload keys `user`, `user_info`, `auth_user`
  - supports fallback identity from `email`/`user_name` session keys

### Changed
- Improved user resolution logic for legacy/custom auth apps where `Auth::user()` is null.

### Fixed
- Prevented false 403 for logged-in users in non-standard session auth implementations.

## v0.0.19 - 2026-05-09
### Added
- Session-based fallback user resolution for custom login flows (`user_id`, `auth_user_id`, `login_user_id`, `id`).

### Changed
- Default middleware set to `['web']` while keeping `auth_required => true`.
- Login enforcement now relies on package access checks to avoid redirect loops in apps with non-standard auth middleware behavior.

### Fixed
- Prevented false 403 for logged-in users in custom session-auth applications.
