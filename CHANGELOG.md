# Changelog

## v0.0.14 - 2026-05-09
### Added
- Polished icon-based mode switch button for theme mode toggle.

### Changed
- Replaced text toggle button with compact sun/moon icon control.
- Improved toggle button sizing, alignment, and accessibility labels.

### Fixed
- Better visual consistency with professional/live dashboard UI.

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
