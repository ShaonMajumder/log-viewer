# Default Login-Required Access (v0.0.18)

## Summary
Adjusted package defaults so log viewer requires login, while allowing any authenticated user.

## Files Changed
- `config/log-viewer.php`
- `CHANGELOG.md`

## Config or Migration Impact
- Fresh installs require authenticated sessions by default.
- Existing apps with published config keep current behavior until re-published.

## Test Coverage Notes
- Verified default config now enforces auth middleware and authenticated-user authorization.
