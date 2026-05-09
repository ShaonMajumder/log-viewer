# Changelog

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
