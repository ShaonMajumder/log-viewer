# Custom Session Auth Compatibility (v0.0.19)

## Summary
Improved login detection for custom-auth Laravel apps where `auth` middleware redirects despite active session login.

## Files Changed
- `src/Http/Controllers/LaravelLogController.php`
- `config/log-viewer.php`
- `CHANGELOG.md`

## Config or Migration Impact
- Default `middleware` now `['web']`.
- `auth_required` remains `true`.
- Package enforces login internally and supports session key fallback for user resolution.

## Test Coverage Notes
- Verified fallback resolves user by session IDs when `Auth::user()` is null.
- Verified unauthorized users still receive deny handling.
