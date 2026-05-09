# Custom Auth Heuristics (v0.0.25)

## Summary
Improves logged-in user detection for custom/legacy Laravel auth flows where `Auth::user()` may be null but session auth state exists.

## Files Changed
- `src/Http/Controllers/LaravelLogController.php`
- `CHANGELOG.md`

## Config / Migration Impact
- No config key changes.

## Test Coverage Notes
- Manual validation in consuming app:
  - logged-in user can open `/log-viewer`
  - guest user still redirects to login when `auth_required = true`
