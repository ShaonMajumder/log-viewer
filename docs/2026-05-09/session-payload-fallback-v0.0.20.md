# Session Payload Fallback Compatibility (v0.0.20)

## Summary
Added broader fallback support for custom auth sessions where user identity is stored directly in session payloads instead of Laravel guard context.

## Files Changed
- `src/Http/Controllers/LaravelLogController.php`
- `CHANGELOG.md`

## Config or Migration Impact
- No config changes required.
- Existing apps benefit automatically after upgrade.

## Test Coverage Notes
- Verified user resolution path checks object/array session payloads and legacy identity keys.
