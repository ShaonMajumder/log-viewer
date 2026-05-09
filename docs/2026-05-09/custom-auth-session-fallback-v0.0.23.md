# Custom Auth Session Fallback (v0.0.23)

## Summary
Improves `/log-viewer` access behavior for apps with custom authentication/session handling so logged-in users are not blocked with false `403` responses.

## Files Changed
- `src/Http/Controllers/LaravelLogController.php`
- `config/log-viewer.php`
- `CHANGELOG.md`

## Config / Migration Impact
- Default `middleware` is now `['web', 'auth']`.
- No breaking config key removals or renames.

## Test Coverage Notes
- Manual validation recommended in host app:
  - Guest access to `/log-viewer` redirects to login.
  - Logged-in user can access `/log-viewer`.
  - Custom session-based login still passes package access checks.
