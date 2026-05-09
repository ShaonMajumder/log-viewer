# Default Public Access Config Update (v0.0.12)

## Summary
Adjusted default package config so log viewer works without auth by default.

## Files Changed
- `config/log-viewer.php`
- `CHANGELOG.md`

## Config or Migration Impact
- New default behavior for fresh installs:
  - `middleware` => `['web']`
  - `auth_required` => `false`
  - `authorize` callback returns `true`
- Existing apps with published config keep their local settings until re-published.

## Test Coverage Notes
- Verified default config no longer requires auth middleware for basic page access.
