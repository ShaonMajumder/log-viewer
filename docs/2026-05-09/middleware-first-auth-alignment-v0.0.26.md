# Middleware-First Auth Alignment (v0.0.26)

## Summary
Aligned package access control with common Laravel log-viewer behavior (like rap2hpoutre style): route middleware is treated as the primary auth gate, with package checks acting as a secondary layer.

## Files Changed
- `src/Http/Controllers/LaravelLogController.php`
- `config/log-viewer.php`
- `README.md`
- `CHANGELOG.md`

## Config / Migration Impact
- Default `authorize` callback now returns `true`.
- Existing strict access control can still be applied via `allowed_emails` and custom `authorize` callback.

## Test Coverage Notes
- Manual checks recommended:
  - with `middleware => ['web', 'auth']`, logged-in users can open `/log-viewer`
  - guests are redirected by auth middleware/login flow
  - optional restrictions (`allowed_emails`, custom `authorize`) still enforce policy
