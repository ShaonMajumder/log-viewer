# Changelog

## v0.0.17 - 2026-05-09
### Added
- N/A

### Changed
- Default package access is now public by default:
  - `middleware` => `['web']`
  - `auth_required` => `false`
  - `authorize` callback returns `true`

### Fixed
- Removes default login requirement so `/log-viewer` is accessible to all users out of the box.

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
