# Default Public Access (v0.0.17)

## Summary
Changed package defaults so `/log-viewer` is accessible to all users without login by default.

## Files Changed
- `config/log-viewer.php`
- `CHANGELOG.md`

## Config or Migration Impact
- Fresh installs now default to public access.
- Existing apps with published config keep current behavior until re-published or manually updated.

## Test Coverage Notes
- Verified default config no longer requires auth middleware.
