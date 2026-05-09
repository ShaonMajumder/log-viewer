# Custom Session Middleware Compatibility (v0.0.24)

## Summary
Prevents `/log-viewer` from being redirected by Laravel `auth` middleware before package-level session-aware authorization can run.

## Files Changed
- `config/log-viewer.php`
- `CHANGELOG.md`

## Config / Migration Impact
- Default middleware changed to `['web']`.
- Access control remains enabled through package config (`auth_required`, `authorize`, `allowed_emails`, unauthorized redirect settings).

## Test Coverage Notes
- Manual validation in consuming app:
  - Guest hits `/log-viewer` and is redirected to login by package access handling.
  - Logged-in custom-session users can open `/log-viewer` without false redirect/403.
