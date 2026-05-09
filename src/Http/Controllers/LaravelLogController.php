<?php

namespace Shaon\LogViewer\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LaravelLogController extends Controller
{
    public function index()
    {
        $access = $this->ensureAccess();
        if ($access instanceof RedirectResponse) {
            return $access;
        }

        $files = $this->discoverLogFiles();
        $selected = (string) request()->query('file', '');
        $search = trim((string) request()->query('q', ''));
        $level = Str::upper(trim((string) request()->query('level', 'ALL')));
        $contextSize = (int) request()->query('context', 10);
        $lines = (int) request()->query('lines', 0);

        $allowedLineOptions = [0, 100, 300, 500, 1000, 2000];
        $allowedContextOptions = [0, 2, 5, 10, 20];
        $allowedLevels = ['ALL', 'ERROR', 'WARNING', 'INFO'];

        if (!in_array($lines, $allowedLineOptions, true)) {
            $lines = 0;
        }
        if (!in_array($contextSize, $allowedContextOptions, true)) {
            $contextSize = 10;
        }
        if (!in_array($level, $allowedLevels, true)) {
            $level = 'ALL';
        }

        if ($selected === '' && !empty($files)) {
            $selected = $files[0]['relative_path'];
        }

        $content = $selected !== '' ? $this->readLogFile($selected) : 'No log file found.';
        if ($lines > 0) {
            $content = $this->tailLogContent($content, $lines);
        }

        $filteredContent = $this->filterLogContent($content, $search, $level);
        $filtersActive = ($search !== '') || ($level !== 'ALL');
        $matchedEntries = $this->buildMatchedEntries($content, $search, $level, $contextSize, $filtersActive);

        return view('log-viewer::index', [
            'layout' => config('log-viewer.layout', 'backend.layouts.app'),
            'heading' => config('log-viewer.heading', 'প্রোডাকশন লগ'),
            'files' => $files,
            'selected_file' => $selected,
            'content' => $filteredContent,
            'content_html' => $this->highlightLogContent($filteredContent),
            'search' => $search,
            'level' => $level,
            'lines' => $lines,
            'context' => $contextSize,
            'filters_active' => $filtersActive,
            'matched_entries' => $matchedEntries,
        ]);
    }

    public function download()
    {
        $access = $this->ensureAccess();
        if ($access instanceof RedirectResponse) {
            return $access;
        }

        $relativePath = (string) request()->query('file', '');
        $search = trim((string) request()->query('q', ''));
        $level = Str::upper(trim((string) request()->query('level', 'ALL')));
        $lines = (int) request()->query('lines', 0);

        $allowedLineOptions = [0, 100, 300, 500, 1000, 2000];
        $allowedLevels = ['ALL', 'ERROR', 'WARNING', 'INFO'];

        if (!in_array($lines, $allowedLineOptions, true)) {
            $lines = 0;
        }
        if (!in_array($level, $allowedLevels, true)) {
            $level = 'ALL';
        }

        if ($relativePath === '') {
            abort(404, 'No log file selected.');
        }

        $fullPath = $this->resolveLogFullPath($relativePath);
        if ($fullPath === '' || !File::exists($fullPath) || !File::isFile($fullPath)) {
            abort(404, 'Selected log file not found.');
        }

        $content = $this->readLogFile($relativePath);
        if ($lines > 0) {
            $content = $this->tailLogContent($content, $lines);
        }
        $content = $this->filterLogContent($content, $search, $level);

        $baseName = pathinfo(basename($fullPath), PATHINFO_FILENAME);
        $downloadName = $baseName . '-filtered.log';

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $downloadName . '"',
        ]);
    }

    private function ensureAccess(): ?RedirectResponse
    {
        $user = $this->resolveCurrentUserSafely();
        $hasSessionAuth = $this->hasAuthenticatedSessionHint();
        $usesAuthMiddleware = $this->routeUsesAuthMiddleware();
        $isAuthenticated = $user !== null || $hasSessionAuth;
        $authRequired = (bool) config('log-viewer.auth_required', true);
        $allowedEmails = array_values(array_filter(array_map(
            static fn ($email) => Str::lower(trim((string) $email)),
            (array) config('log-viewer.allowed_emails', [])
        )));
        $authorize = config('log-viewer.authorize');

        // Align with common Laravel log-viewer behavior: if route auth middleware is present,
        // trust middleware as the primary gate and avoid false negatives from custom user resolvers.
        if ($authRequired && !$isAuthenticated && !$usesAuthMiddleware) {
            return $this->handleUnauthorized($authRequired);
        }

        // In custom auth/session flows, we may have a valid session but no resolved user model.
        // In that case, skip user-object checks and allow access when auth is otherwise satisfied.
        if ($user === null && ($isAuthenticated || ($authRequired && $usesAuthMiddleware))) {
            return null;
        }

        if (!empty($allowedEmails)) {
            $currentEmail = Str::lower(trim((string) ($user->email ?? '')));
            if ($currentEmail === '' || !in_array($currentEmail, $allowedEmails, true)) {
                return $this->handleUnauthorized($authRequired);
            }
        }

        if (is_callable($authorize) && !$authorize($user)) {
            return $this->handleUnauthorized($authRequired);
        }

        return null;
    }

    private function routeUsesAuthMiddleware(): bool
    {
        try {
            $middlewares = (array) config('log-viewer.middleware', []);
            foreach ($middlewares as $middleware) {
                if (!is_string($middleware)) {
                    continue;
                }

                if ($middleware === 'auth' || Str::startsWith($middleware, 'auth:')) {
                    return true;
                }
            }

            return false;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function resolveCurrentUserSafely()
    {
        try {
            // Prefer the user attached to current request context first.
            $requestUser = request()->user();
            if ($requestUser) {
                return $requestUser;
            }

            if (!app()->bound('auth')) {
                return null;
            }

            // Guard can be configured for apps that do not authenticate on default guard.
            $guard = (string) config('log-viewer.auth_guard', config('auth.defaults.guard', 'web'));
            if ($guard !== '') {
                $guardUser = Auth::guard($guard)->user();
                if ($guardUser) {
                    return $guardUser;
                }
            }

            // Fallback for custom login flows: resolve user from session IDs.
            $session = request()->session();
            if ($session) {
                // Some apps store user payload directly in session.
                foreach (['user', 'user_info', 'auth_user'] as $payloadKey) {
                    $payload = $session->get($payloadKey);
                    if (is_object($payload)) {
                        return $payload;
                    }
                    if (is_array($payload) && !empty($payload)) {
                        return (object) $payload;
                    }
                }

                $sessionKeys = ['user_id', 'auth_user_id', 'login_user_id', 'id'];
                $userId = null;
                foreach ($sessionKeys as $key) {
                    $value = $session->get($key);
                    if ($value !== null && $value !== '') {
                        $userId = $value;
                        break;
                    }
                }

                if ($userId !== null) {
                    $modelClass = (string) config('auth.providers.users.model', '');
                    if ($modelClass !== '' && class_exists($modelClass) && method_exists($modelClass, 'query')) {
                        $resolved = $modelClass::query()->find($userId);
                        if ($resolved) {
                            return $resolved;
                        }
                    }
                }

                // Last-resort identity fallback for legacy custom auth sessions.
                $email = $session->get('email') ?? $session->get('user_email') ?? null;
                $username = $session->get('user_name') ?? $session->get('username') ?? null;
                if ($email || $username) {
                    return (object) [
                        'email' => $email,
                        'user_name' => $username,
                    ];
                }
            }

            // Fallback to default facade user resolution.
            return Auth::user();
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function hasAuthenticatedSessionHint(): bool
    {
        try {
            if (app()->bound('auth')) {
                try {
                    if (Auth::check()) {
                        return true;
                    }
                } catch (\Throwable $e) {
                    // Continue with session-based heuristics.
                }
            }

            if (!request()->hasSession()) {
                return false;
            }

            $session = request()->session();
            if (!$session || !$session->isStarted()) {
                return false;
            }

            // Laravel auth stores guard login entries like: login_web_<hash>
            foreach ($session->all() as $key => $value) {
                if (is_string($key) && Str::startsWith($key, 'login_') && !empty($value)) {
                    return true;
                }
            }

            // Common custom-auth identifiers used in legacy apps.
            foreach (['user_id', 'auth_user_id', 'login_user_id', 'id', 'email', 'user_email', 'user_name', 'username'] as $key) {
                $value = $session->get($key);
                if ($value !== null && $value !== '') {
                    return true;
                }
            }

            return false;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function handleUnauthorized(bool $authRequired): ?RedirectResponse
    {
        if (!$authRequired) {
            return null;
        }

        $action = (string) config('log-viewer.unauthorized_action', 'abort');
        
        if ($action === 'redirect') {
            $target = (string) config('log-viewer.unauthorized_redirect_to', '/');
            $target = $target !== '' ? $target : '/';
            $intended = request()->fullUrl();

            if (request()->hasSession()) {
                request()->session()->put('url.intended', $intended);
            }

            return redirect()->to($target);
        }

        abort(403, 'Unauthorized to view Laravel logs.');
    }

    private function discoverLogFiles(): array
    {
        $paths = [];

        foreach (File::glob(storage_path('logs/laravel/*/laravel-*.log')) ?: [] as $path) {
            $paths[] = $path;
        }

        foreach (File::glob(storage_path('logs/laravel/*/laravel.log')) ?: [] as $path) {
            $paths[] = $path;
        }

        foreach (File::glob(storage_path('logs/laravel-*.log')) ?: [] as $path) {
            $paths[] = $path;
        }

        $singleLog = storage_path('logs/laravel.log');
        if (File::exists($singleLog)) {
            $paths[] = $singleLog;
        }

        $files = [];
        foreach (array_unique($paths) as $path) {
            $relative = str_replace(storage_path('logs') . DIRECTORY_SEPARATOR, '', $path);
            $files[] = [
                'relative_path' => str_replace('\\', '/', $relative),
                'label' => str_replace(storage_path('logs') . DIRECTORY_SEPARATOR, '', $path),
                'timestamp' => @filemtime($path) ?: 0,
            ];
        }

        usort($files, fn ($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        return $files;
    }

    private function readLogFile(string $relativePath): string
    {
        $fullPath = $this->resolveLogFullPath($relativePath);

        if ($fullPath === '' || !File::exists($fullPath) || !File::isFile($fullPath)) {
            return 'Selected log file not found.';
        }

        $content = File::get($fullPath);
        $maxBytes = 1024 * 1024;
        if (strlen($content) > $maxBytes) {
            $content = "[Showing last 1MB]\n" . substr($content, -$maxBytes);
        }

        return $content;
    }

    private function tailLogContent(string $content, int $lines): string
    {
        $allLines = preg_split('/\R/', $content) ?: [];
        if (empty($allLines)) {
            return $content;
        }

        return implode("\n", array_slice($allLines, -$lines));
    }

    private function filterLogContent(string $content, string $search, string $level): string
    {
        $lines = preg_split('/\R/', $content) ?: [];
        $search = Str::lower($search);
        $filteredLines = [];

        foreach ($lines as $line) {
            if ($level !== 'ALL' && $this->detectLineLevel($line) !== $level) {
                continue;
            }
            if ($search !== '' && !Str::contains(Str::lower($line), $search)) {
                continue;
            }
            $filteredLines[] = $line;
        }

        return empty($filteredLines) ? 'No log lines matched your filter.' : implode("\n", $filteredLines);
    }

    private function detectLineLevel(string $line): string
    {
        if (stripos($line, 'ERROR') !== false || stripos($line, '.error:') !== false) {
            return 'ERROR';
        }
        if (stripos($line, 'WARNING') !== false || stripos($line, '.warning:') !== false) {
            return 'WARNING';
        }
        if (stripos($line, 'INFO') !== false || stripos($line, '.info:') !== false) {
            return 'INFO';
        }

        return 'DEFAULT';
    }

    private function resolveLogFullPath(string $relativePath): string
    {
        $clean = ltrim(str_replace('..', '', $relativePath), '/\\');
        if ($clean === '') {
            return '';
        }

        return storage_path('logs/' . $clean);
    }

    private function highlightLogContent(string $content): string
    {
        $lines = preg_split('/\R/', $content) ?: [];
        if (empty($lines)) {
            return '';
        }

        $htmlLines = [];
        foreach ($lines as $line) {
            $htmlLines[] = $this->renderHighlightedLine($line);
        }

        return implode("\n", $htmlLines);
    }

    private function renderHighlightedLine(string $line): string
    {
        $levelClass = 'log-level-default';
        if (stripos($line, 'ERROR') !== false || stripos($line, '.error:') !== false) {
            $levelClass = 'log-level-error';
        } elseif (stripos($line, 'WARNING') !== false || stripos($line, '.warning:') !== false) {
            $levelClass = 'log-level-warning';
        } elseif (stripos($line, 'INFO') !== false || stripos($line, '.info:') !== false) {
            $levelClass = 'log-level-info';
        }

        return '<span class="log-line ' . $levelClass . '">' . e($line) . '</span>';
    }

    private function renderLineRow(int $lineNo, string $line): string
    {
        return '<div class="log-line-row"><span class="log-line-no">L' . $lineNo . '</span><div>'
            . $this->renderHighlightedLine($line)
            . '</div></div>';
    }

    private function buildMatchedEntries(string $content, string $search, string $level, int $contextSize, bool $filtersActive): array
    {
        if (!$filtersActive) {
            return [];
        }

        $lines = preg_split('/\R/', $content) ?: [];
        if (empty($lines)) {
            return [];
        }

        $searchNeedle = Str::lower($search);
        $matchedIndexes = [];

        foreach ($lines as $idx => $line) {
            if ($level !== 'ALL' && $this->detectLineLevel($line) !== $level) {
                continue;
            }
            if ($searchNeedle !== '' && !Str::contains(Str::lower($line), $searchNeedle)) {
                continue;
            }
            $matchedIndexes[] = $idx;
        }

        $entries = [];
        foreach ($matchedIndexes as $idx) {
            $beforeHtmlLines = [];
            $afterHtmlLines = [];

            if ($contextSize > 0) {
                $contextStart = max(0, $idx - $contextSize);
                $contextEnd = min(count($lines) - 1, $idx + $contextSize);

                for ($i = $contextStart; $i < $idx; $i++) {
                    $beforeHtmlLines[] = $this->renderLineRow($i + 1, $lines[$i]);
                }
                for ($i = $idx + 1; $i <= $contextEnd; $i++) {
                    $afterHtmlLines[] = $this->renderLineRow($i + 1, $lines[$i]);
                }
            }

            $entries[] = [
                'id' => 'match-' . ($idx + 1),
                'line_no' => $idx + 1,
                'main_html' => $this->renderHighlightedLine($lines[$idx]),
                'before_html' => implode("\n", $beforeHtmlLines),
                'after_html' => implode("\n", $afterHtmlLines),
            ];
        }

        return $entries;
    }
}
