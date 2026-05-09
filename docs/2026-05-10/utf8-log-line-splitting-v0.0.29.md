# UTF-8 Log Line Splitting Fix

## Summary
Fixed log line splitting so UTF-8/Bangla text inside a Laravel log record is not incorrectly treated as a line break.

## Files Changed
- `src/Http/Controllers/LaravelLogController.php`

## Config/Migration Impact
- No config changes.
- No migration required.

## Test Coverage Notes
- Verified the package controller no longer uses `preg_split('/\R/', ...)`.
- The fix now splits log content only on real file line endings: CRLF, LF, or CR.
