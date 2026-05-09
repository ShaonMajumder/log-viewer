# Changelog

## v0.0.18 - 2026-05-09
### Added
- N/A

### Changed
- Default access restored to login-required while allowing all authenticated users:
  - `middleware` => `['web', 'auth']`
  - `auth_required` => `true`
  - `authorize` callback returns `(bool) $user`

### Fixed
- Aligns default behavior with expected “logged-in users can view” policy.

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
