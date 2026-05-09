# 🚀 Laravel Log Viewer

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-9%20%7C%2010%20%7C%2011-red?style=for-the-badge&logo=laravel" />
    <img src="https://img.shields.io/badge/PHP-8%2B-blue?style=for-the-badge&logo=php" />
    <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" />
    <img src="https://img.shields.io/badge/Production-Ready-success?style=for-the-badge" />
</p>

<p align="center">
    🔥 Beautiful • Secure • Fast • Real-Time Friendly Laravel Log Viewer
</p>

---

# ✨ Features

## 🛡️ Security First

- 🔐 Admin-protected routes
- 🚫 Path traversal protection
- 🧠 Custom authorization callback
- 🧱 Middleware configurable

---

## 📄 Smart Log Discovery

Automatically detects:

- `laravel.log`
- `laravel-YYYY-MM-DD.log`
- rotated log files
- multiple Laravel log patterns

---

## 🎨 Beautiful Log Highlighting

Color-coded levels:

| Level      | Highlight |
| ---------- | --------- |
| ❌ ERROR   | Red       |
| ⚠️ WARNING | Yellow    |
| ℹ️ INFO    | Blue      |
| 🐛 DEBUG   | Gray      |

---

## 🌙 Theme Support

Built-in dark theme options:

- `Dark Ink` (default)
- `Dark Graphite`
- `Dark Forest`

Theme can be changed directly from the viewer header, and the selection is saved in browser `localStorage`.

---

## 🔍 Advanced Filtering

- 🔎 Full-text search
- 🎯 Level filtering
- 📌 Match-only mode
- ⚡ Instant filtering

---

## 🧠 Inline Context Expansion

Expand surrounding lines around matches:

- `No Context`
- `±2`
- `±5`
- `±10`
- `±20`

Perfect for debugging stack traces and exceptions.

---

## ⏱️ Tail Mode Support

View latest logs instantly:

- No Limit
- 100
- 300
- 500
- 1000
- 2000

---

## 📥 Filtered Download

Download:

- full logs
- filtered results
- contextual output

---

## 🔄 Auto Refresh Controls

- Live refresh
- Pause on scroll
- Smart refresh handling
- Developer-friendly UX

---

# 📦 Installation

## ✅ Packagist (Recommended)

```bash
composer require shaonmajumder/log-viewer
```

---

## 🧪 Local Development (Path Repository)

Add to your Laravel app:

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

---

# ⚙️ Publish Configuration

```bash
php artisan vendor:publish --tag=log-viewer-config
```

Publish views:

```bash
php artisan vendor:publish --tag=log-viewer-views
```

---

# 🌐 Default Routes

| Route                   | Description     |
| ----------------------- | --------------- |
| `/log-viewer`           | Main log viewer |
| `/log-viewer/download`  | Download logs   |

---

# 🔧 Configuration

Published config:

```bash
config/log-viewer.php
```

---

## Important Options

| Option         | Description          |
| -------------- | -------------------- |
| `route_prefix` | Route prefix         |
| `middleware`   | Route middleware     |
| `auth_required` | Require logged-in user |
| `allowed_emails` | Optional email allow-list |
| `unauthorized_action` | `abort` (403) or `redirect` |
| `unauthorized_redirect_to` | Redirect target path |
| `authorize`    | Custom access policy |
| `layout`       | Blade layout         |
| `heading`      | Viewer heading       |

---

# 🔒 Access Control Examples

Allow only specific emails:

```php
'allowed_emails' => ['dev1@example.com', 'dev2@example.com'],
```

Use `403` instead of redirect when unauthorized:

```php
'unauthorized_action' => 'abort',
```

Redirect unauthorized users to home:

```php
'unauthorized_action' => 'redirect',
'unauthorized_redirect_to' => '/',
```

Allow viewing without auth/admin guard (internal trusted environments only):

```php
'middleware' => ['web'],
'auth_required' => false,
'allowed_emails' => [],
'authorize' => static fn ($user): bool => true,
```

Default behavior is middleware-first (similar to common Laravel log-viewers): when `auth` middleware is configured, access is primarily enforced by middleware and package-level checks avoid false denials in custom auth/session setups.

---

# 🔐 Admin Only Example

```php
'authorize' => static function ($user): bool {
    return $user && (int) ($user->is_admin ?? 0) === 1;
},
```

---

# 🖼️ Screenshots

```md
![Main Viewer](assets/screenshots/log-viewer-main.png)

![Context Expansion](assets/screenshots/log-viewer-context.png)

![Auto Refresh](assets/screenshots/log-viewer-refresh.png)
```

---

# 🧱 Package Structure

```text
src/
├── Http/
│   └── Controllers/
│       └── LaravelLogController.php
│
├── LaravelLogViewerServiceProvider.php

routes/
└── web.php

config/
└── log-viewer.php

resources/
└── views/
    └── index.blade.php
```

---

# 🛡️ Security Notes

> ⚠️ Never expose production logs publicly.

Recommended:

- protect with authentication
- enforce admin authorization
- restrict access internally only

---

# 🚀 Built For

✅ Production Laravel apps  
✅ Admin panels  
✅ DevOps dashboards  
✅ Debugging tools  
✅ Monitoring systems  
✅ Internal engineering tools

---

# ❤️ Why This Package?

Most Laravel log viewers are:

- outdated
- unsafe
- slow
- ugly
- missing filtering/context tools

This package focuses on:

✅ Developer Experience  
✅ Security  
✅ Performance  
✅ Clean UI  
✅ Real-world production usage

---

# 📌 Compatibility

| Framework  | Supported |
| ---------- | --------- |
| Laravel 9  | ✅        |
| Laravel 10 | ✅        |
| Laravel 11 | ✅        |

| PHP Version | Supported |
| ----------- | --------- |
| PHP 8+      | ✅        |

---

# 📄 License

MIT © Shaon Majumder

---

# ⭐ Support The Project

If this package helps you:

- ⭐ Star the repository
- 🍴 Fork it
- 🐛 Report issues
- 🚀 Contribute improvements

---

<p align="center">
    Built with ❤️ for Laravel developers
</p>
