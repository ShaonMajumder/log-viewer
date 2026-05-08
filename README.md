# shaonmajumder/log-viewer

A production-ready Laravel log viewer package with level highlighting, smart filtering, tail mode, inline context expansion, filtered download, and admin-safe access control.

## Features

- Admin-protected log viewer endpoint
- Multi-pattern Laravel log file discovery
- Safe file path handling (prevents path traversal)
- Level-based highlighting (`ERROR`, `WARNING`, `INFO`)
- Search + level filters
- Tail mode (`No limit`, `100`, `300`, `500`, `1000`, `2000`)
- Inline context expansion around matched lines
  - `No context`, `±2`, `±5`, `±10`, `±20`
  - Expanded order: before lines -> target line -> after lines
- Target line visual emphasis in expanded mode
- Filtered-view download
- Auto-refresh controls and pause-at-scroll behavior in UI

## Compatibility

- PHP: `^8.0`
- Laravel: `^9.0 | ^10.0 | ^11.0`

## Installation

### Option A: Packagist (recommended)

```bash
composer require shaonmajumder/log-viewer
```

### Option B: Local path repository (sister folder)

In your Laravel app `composer.json`:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../log-viewer"
    }
  ],
  "require": {
    "shaonmajumder/log-viewer": "*@dev"
  }
}
```

Then run:

```bash
composer update shaonmajumder/log-viewer
```

## Publish Config / Views

```bash
php artisan vendor:publish --tag=laravel-log-viewer-config
php artisan vendor:publish --tag=laravel-log-viewer-views
```

## Default Routes

- `GET /laravel-log`
- `GET /laravel-log/download`

These are configurable via `config/laravel-log-viewer.php`.

## Configuration

Published config: `config/laravel-log-viewer.php`

Key options:

- `route_prefix` (default: `laravel-log`)
- `route_name_prefix` (default: `laravel.log.`)
- `middleware` (default: `['web','auth']`)
- `authorize` closure (custom access policy)
- `layout` (base Blade layout)
- `heading` (viewer page heading)

### Example strict admin-only authorization

```php
'authorize' => static function ($user): bool {
    return $user && (int) ($user->is_admin ?? 0) === 1;
},
```

## Screenshots

Add your package screenshots to this repository (example path: `assets/screenshots/`) and reference them here.

Example markdown:

```md
![Log Viewer Main](assets/screenshots/log-viewer-main.png)
![Context Expansion](assets/screenshots/log-viewer-context.png)
![Auto Refresh Controls](assets/screenshots/log-viewer-refresh.png)
```

## Usage Notes

- Context expansion appears when filter result mode is active.
- `No context` shows only matched lines.
- Download button can be wired to export full file or filtered view (package supports filtered output path).
- Viewer reads from `storage/logs` and supports common Laravel rotation patterns.

## Security Notes

- Keep route behind authentication middleware.
- Always enforce your own `authorize` closure for production.
- Do not expose viewer routes publicly.

## Package Structure

- `src/LaravelLogViewerServiceProvider.php`
- `src/Http/Controllers/LaravelLogController.php`
- `routes/web.php`
- `config/laravel-log-viewer.php`
- `resources/views/index.blade.php`

## License

MIT
