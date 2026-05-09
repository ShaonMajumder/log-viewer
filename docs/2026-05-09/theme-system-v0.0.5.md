# Theme System Release (v0.0.5)

## Summary
Introduced multi-theme dark mode support to the package UI and documented release/versioning guardrails.

## Files Changed
- `resources/views/index.blade.php`
- `README.md`
- `AGENTS.md`
- `CHANGELOG.md`

## Config or Migration Impact
- No breaking config changes.
- No migrations required.

## Test Coverage Notes
- Manually verified in browser:
  - Theme switcher renders and switches themes.
  - Selected theme persists after refresh.
  - Existing filter/search/context interactions still work.
