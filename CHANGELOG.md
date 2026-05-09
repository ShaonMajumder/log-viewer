# Changelog

## v0.0.27 - 2026-05-09
### Added
- N/A

### Changed
- Removed package access-control config dependencies (`auth_required`, `auth_guard`, `authorize`, `unauthorized_action`, `unauthorized_redirect_to`) from default config.
- Access behavior is now fixed in package code: guests redirect to `/login`, logged-in users can access logs.

### Fixed
- Eliminated redirect/403 inconsistencies caused by app-specific auth config drift.

## v0.0.26 - 2026-05-09
### Added
- Added middleware-aware auth detection in access control flow.

### Changed
- Authorization flow is now middleware-first (aligned with common Laravel log-viewer behavior).
- Default `authorize` callback now returns `true` to avoid blocking valid logged-in sessions in custom auth setups.

### Fixed
- Prevented logged-in users from being redirected/denied due to `Auth::user()` resolver mismatch when `auth` middleware is already guarding the route.

## v0.0.25 - 2026-05-09
### Added
- Added broader custom session authentication key detection (`user_id`, `email`, `username`, etc.).

### Changed
- Access check now also uses `Auth::check()` when available before session heuristics.

### Fixed
- Reduced false redirects for logged-in users in non-standard Laravel auth/session implementations.

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
