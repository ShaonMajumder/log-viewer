# Feature Parity Update for /log-viewer (v0.0.13)

## Summary
Ported missing legacy `/laravel-log` UX controls into package `/log-viewer` while preserving the package theme engine.

## Files Changed
- `resources/views/index.blade.php`
- `CHANGELOG.md`

## Config or Migration Impact
- No breaking config changes.
- Existing published views should be re-published with `--force` to receive UI updates.

## Test Coverage Notes
- Verified new UI controls render and function:
  - reset filters
  - download actions
  - auto-refresh timer + pause behavior
  - light/dark pairing toggle per theme family
