# Route Name Collision + Web Middleware Group Fix (v0.0.28)

## Summary
Fixed two runtime issues causing redirect/access instability in host apps:
1. Route name collision with host `laravel.log.*` routes.
2. Middleware filtering bug that removed `web` middleware group.

## Files Changed
- `routes/web.php`
- `config/log-viewer.php`
- `resources/views/index.blade.php`
- `CHANGELOG.md`

## Config / Migration Impact
- Default `route_name_prefix` is now `log.viewer.`.
- If app has published `config/log-viewer.php`, update:
  - `'route_name_prefix' => 'log.viewer.'`
- Existing custom prefixes still work.

## Test Coverage Notes
- Manual checks:
  - `route('log.viewer.index')` resolves to `/log-viewer`
  - `/log-viewer` route has `web` middleware attached
  - no fallback to host `laravel-log` route names
