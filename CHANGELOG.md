# Changelog

## v0.0.16 - 2026-05-09
### Added
- N/A

### Changed
- Default package access configuration restored to authenticated mode:
  - `middleware` => `['web', 'auth']`
  - `auth_required` => `true`
  - `authorize` callback requires authenticated user

### Fixed
- Prevents unauthenticated access by default on fresh installs.

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
