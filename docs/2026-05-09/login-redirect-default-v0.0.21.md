# Login Redirect Default Behavior (v0.0.21)

## Summary
Package defaults now redirect unauthenticated users to `/login` while allowing any logged-in user to access `/log-viewer`.

## Files Changed
- `config/log-viewer.php`
- `CHANGELOG.md`

## Config or Migration Impact
- Fresh installs use:
  - `middleware` => `['web']`
  - `auth_required` => `true`
  - `unauthorized_action` => `redirect`
  - `unauthorized_redirect_to` => `/login`

## Test Coverage Notes
- Verified guest requests are redirected to login by default.
- Verified authenticated requests pass access checks.
