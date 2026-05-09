# Intended Login Redirect Fix (v0.0.22)

## Summary
When unauthenticated users access `/log-viewer`, package now stores intended URL before redirecting to login so successful authentication can return users to the log viewer page.

## Files Changed
- `src/Http/Controllers/LaravelLogController.php`
- `CHANGELOG.md`

## Config or Migration Impact
- No config changes required.

## Test Coverage Notes
- Verified package sets `url.intended` to current request URL before redirecting.
