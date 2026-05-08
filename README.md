# robist/laravel-log-viewer-package

Reusable Laravel log viewer package extracted from `gcc_v4`.

## Install (from sibling path)

In your Laravel app `composer.json`:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../laravel-log-viewer-package"
    }
  ],
  "require": {
    "robist/laravel-log-viewer-package": "*@dev"
  }
}
```

Then run:

```bash
composer update robist/laravel-log-viewer-package
php artisan vendor:publish --tag=laravel-log-viewer-config
```

## Routes

- `GET /laravel-log`
- `GET /laravel-log/download`

Route prefix/name/middleware are configurable in `config/laravel-log-viewer.php`.

## Access control

Use the `authorize` closure in config to apply your own admin policy.

## Notes

- Package scans logs under `storage/logs`.
- Supports filtering, level highlighting, optional context expansion, and filtered download.
