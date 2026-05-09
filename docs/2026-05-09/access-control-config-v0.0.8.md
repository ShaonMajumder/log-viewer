# Access Control Config Update (v0.0.8)

## Summary
Introduced configurable access control with email allow-list and configurable unauthorized behavior (403 or redirect), including support for environments where auth/admin guard is intentionally disabled.

## Files Changed
- `config/log-viewer.php`
- `src/Http/Controllers/LaravelLogController.php`
- `README.md`
- `CHANGELOG.md`

## Config or Migration Impact
- New config keys:
  - `auth_required`
  - `allowed_emails`
  - `unauthorized_action`
  - `unauthorized_redirect_to`
- Default middleware is now `['web']`.
- Existing apps can still enforce auth/admin policy via config values.

## Test Coverage Notes
- Verified unauthorized flow supports both `abort` and `redirect` modes.
- Verified allow-list behavior using configured emails.
- Verified no-auth mode works when explicitly configured.
