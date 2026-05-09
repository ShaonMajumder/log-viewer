# Public Layout and Auth Bypass Update (v0.0.10)

## Summary
Made the package runnable in apps without auth-dependent backend layouts by shipping a package-native layout and refining unauthorized handling when auth is disabled.

## Files Changed
- `resources/views/layouts/app.blade.php`
- `config/log-viewer.php`
- `src/Http/Controllers/LaravelLogController.php`
- `CHANGELOG.md`

## Config or Migration Impact
- Default layout is now `log-viewer::layouts.app`.
- Existing apps can still override `layout` in published config.
- Unauthorized handler now receives `authRequired` and returns `null` when auth is disabled.

## Test Coverage Notes
- Verified package page renders with `auth_required=false` in guest mode.
- Verified no app-layout-auth dependency is required for default package rendering.
