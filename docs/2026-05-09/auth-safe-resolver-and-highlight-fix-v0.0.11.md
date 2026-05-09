# Auth-Safe Resolver and Highlight Method Fix (v0.0.11)

## Summary
Stabilized controller access checks in hostile app bootstrap states and restored missing content highlighting method.

## Files Changed
- `src/Http/Controllers/LaravelLogController.php`
- `CHANGELOG.md`

## Config or Migration Impact
- No config migration required.
- Existing config keys continue to work.

## Test Coverage Notes
- Verified access check path no longer hard-depends on direct `Auth::user()`.
- Verified `highlightLogContent()` method exists and is callable.
