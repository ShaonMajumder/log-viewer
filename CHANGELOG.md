# Changelog

## v0.0.13 - 2026-05-09
### Added
- Feature parity controls in package UI:
  - Reset all filters button
  - Download file / filtered download button
  - Log file pattern hint
  - Auto-refresh timer controls (Off/5/10/30)
  - Pause-at-scroll with saved scroll position
  - Auto-refresh status text

### Changed
- Theme system upgraded to paired families with light/dark correspondence per family.
- Added light/dark mode toggle button mapped to the currently selected theme family.

### Fixed
- Missing UX controls in `/log-viewer` compared to legacy `/laravel-log` view.

## v0.0.12 - 2026-05-09
### Added
- N/A

### Changed
- Default access configuration is now public-friendly:
  - `middleware` default set to `['web']`
  - `auth_required` default set to `false`
  - `authorize` default callback now returns `true`

### Fixed
- Avoid default redirect/deny behavior in environments where auth middleware is not desired.

## v0.0.11 - 2026-05-09
### Added
- Safe current-user resolver to avoid auth/hash container failures during access checks.

### Changed
- `ensureAccess()` now resolves current user through a guarded helper instead of direct `Auth::user()`.

### Fixed
- Restored missing `highlightLogContent()` method to prevent `BadMethodCallException`.

## v0.0.10 - 2026-05-09
### Added
- Package-provided base layout: `log-viewer::layouts.app` for standalone rendering.

### Changed
- Default `layout` config now uses package layout (`log-viewer::layouts.app`) instead of app-specific `backend.layouts.app`.
- Unauthorized handler now receives `authRequired` and bypasses deny action when auth is disabled.

### Fixed
- Prevented guest-mode crashes in apps whose global backend layouts assume authenticated users.

## v0.0.9 - 2026-05-09
### Added
- N/A

### Changed
- Default `middleware` in `config/log-viewer.php` is now `['web', 'auth']`.

### Fixed
- Restored authenticated-by-default behavior for package routes.
