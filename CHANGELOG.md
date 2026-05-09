# Changelog

## v0.0.9 - 2026-05-09
### Added
- N/A

### Changed
- Default `middleware` in `config/log-viewer.php` is now `['web', 'auth']`.

### Fixed
- Restored authenticated-by-default behavior for package routes.

## v0.0.8 - 2026-05-09
### Added
- New access control config options:
  - `auth_required`
  - `allowed_emails`
  - `unauthorized_action`
  - `unauthorized_redirect_to`
- Email allow-list support via `allowed_emails`.
- Configurable unauthorized behavior: `abort` (403) or `redirect`.

### Changed
- Default middleware changed to `['web']` so package-level access handling controls unauthorized behavior consistently.

### Fixed
- Removed forced auth-middleware redirect dependency for access control.

## v0.0.7 - 2026-05-09
### Added
- N/A

### Changed
- Updated default route prefix from `laravel-log` to `log-viewer`.
- Updated route fallback prefix in route registration to `log-viewer`.
- Updated README default route examples to `/log-viewer` and `/log-viewer/download`.

### Fixed
- N/A

## v0.0.6 - 2026-05-09
### Added
- Safe middleware alias filtering in routes so missing aliases (like `auth`) do not crash package bootstrap in minimal app setups.

### Changed
- N/A

### Fixed
- Removed UTF-8 BOM bytes from PHP source files that caused fatal parse errors during package discovery.

## v0.0.5 - 2026-05-09
### Added
- Three built-in dark themes for the log viewer UI:
  - Dark Ink (default)
  - Dark Graphite
  - Dark Forest
- Theme switcher in the viewer header.
- Theme persistence via browser localStorage.
- Contributor guardrails in `AGENTS.md` including git hygiene and version management.

### Changed
- Refactored viewer styles to a CSS variable theme system.
- Updated README with theme support documentation.

### Fixed
- N/A
