# Route Prefix Update (v0.0.7)

## Summary
Changed package default log viewer URL from `/laravel-log` to `/log-viewer`.

## Files Changed
- `config/log-viewer.php`
- `routes/web.php`
- `README.md`
- `CHANGELOG.md`

## Config or Migration Impact
- Fresh installs now use `/log-viewer` by default.
- Existing apps that already published config can keep their custom `route_prefix` unchanged.

## Test Coverage Notes
- Verified route docs and package default/fallback prefixes are aligned.
