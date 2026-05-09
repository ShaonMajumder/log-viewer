# Middleware Default Auth Update (v0.0.9)

## Summary
Updated package default middleware to include `auth` again so authenticated access is enforced by default.

## Files Changed
- `config/log-viewer.php`
- `CHANGELOG.md`

## Config or Migration Impact
- Fresh installs default to `['web', 'auth']`.
- Existing published config remains under application control until re-published/updated manually.

## Test Coverage Notes
- Verified default config value includes both `web` and `auth` middleware.
