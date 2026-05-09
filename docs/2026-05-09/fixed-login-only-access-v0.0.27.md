# Fixed Login-Only Access (v0.0.27)

## Summary
Removed package auth toggle dependencies and made access behavior fixed and predictable:
- guests are redirected to `/login`
- logged-in users can view `/log-viewer`

## Files Changed
- `src/Http/Controllers/LaravelLogController.php`
- `config/log-viewer.php`
- `README.md`
- `CHANGELOG.md`

## Config / Migration Impact
- Removed auth-related config keys from package default config:
  - `auth_required`
  - `auth_guard`
  - `authorize`
  - `unauthorized_action`
  - `unauthorized_redirect_to`
- Existing published app configs may still include these keys; they are now ignored by package access flow.

## Test Coverage Notes
- Manual validation:
  - guest request to `/log-viewer` redirects to `/login`
  - logged-in user can access `/log-viewer`
  - `/log-viewer/download` follows same access rule
