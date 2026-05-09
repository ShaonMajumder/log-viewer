# Changelog

## v0.0.24 - 2026-05-09
### Added
- N/A

### Changed
- Default middleware changed to `['web']` to avoid premature auth-middleware redirects in custom session auth setups.

### Fixed
- `/log-viewer` access now relies on package auth checks (with session fallback) so logged-in custom-session users are not redirected away before controller authorization runs.

## v0.0.23 - 2026-05-09
### Added
- Added authenticated-session fallback detection for custom auth flows.

### Changed
- Default middleware is now `['web', 'auth']` for login-required access.

### Fixed
- Prevented false `403 Unauthorized` when users are logged in but `Auth::user()` is unresolved in custom session setups.

## v0.0.22 - 2026-05-09
### Added
- Sets `url.intended` before unauthorized login redirect.

### Changed
- Unauthorized redirect flow now preserves target log-viewer URL through login.

### Fixed
- Prevented post-login fallback to dashboard when user initially requested `/log-viewer`.

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
