# Changelog

## v0.0.21 - 2026-05-09
### Added
- N/A

### Changed
- Default unauthorized behavior now redirects to login:
  - `unauthorized_action` => `redirect`
  - `unauthorized_redirect_to` => `/login`
- Keeps login-required policy with broad logged-in-user compatibility.

### Fixed
- Default UX now matches expected behavior:
  - guest users are redirected to login
  - logged-in users can access `/log-viewer`

## v0.0.20 - 2026-05-09
### Added
- Extended custom session auth compatibility:
  - supports session payload keys `user`, `user_info`, `auth_user`
  - supports fallback identity from `email`/`user_name` session keys

### Changed
- Improved user resolution logic for legacy/custom auth apps where `Auth::user()` is null.

### Fixed
- Prevented false 403 for logged-in users in non-standard session auth implementations.
