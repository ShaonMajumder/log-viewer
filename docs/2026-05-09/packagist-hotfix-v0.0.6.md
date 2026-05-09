# Packagist Hotfix (v0.0.6)

## Summary
Patch release to fix install/runtime issues reported after `v0.0.5`.

## Files Changed
- `src/LogViewerServiceProvider.php`
- `src/Http/Controllers/LaravelLogController.php`
- `routes/web.php`
- `config/log-viewer.php`
- `CHANGELOG.md`

## Config or Migration Impact
- No migration required.
- Existing config still valid.
- If `auth` middleware alias does not exist in host app, the package now skips that alias instead of crashing at bootstrap.

## Test Coverage Notes
- Verified PHP namespace parse issue resolved by removing BOM.
- Verified route registration no longer hard-fails when unknown middleware alias exists in configured middleware list.
