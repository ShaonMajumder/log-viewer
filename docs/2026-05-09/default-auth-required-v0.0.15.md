# Default Auth Required (v0.0.15)

## Summary
Reverted package defaults to secure access mode so log viewer requires authenticated users.

## Files Changed
- `config/log-viewer.php`
- `CHANGELOG.md`

## Config or Migration Impact
- New default behavior for fresh installs:
  - `middleware` => `['web', 'auth']`
  - `auth_required` => `true`
  - `authorize` callback requires authenticated user
- Existing published app config remains unchanged until re-published.
