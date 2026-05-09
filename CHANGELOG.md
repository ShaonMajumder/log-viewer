# Changelog

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
