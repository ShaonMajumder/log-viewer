@extends($layout)

@section('content')
<style>
    .log-line {
        display: block;
    }
    .log-level-default {
        color: #e2e8f0;
    }
    .log-level-error {
        color: #fecaca;
        background: rgba(127, 29, 29, 0.35);
    }
    .log-level-warning {
        color: #fde68a;
        background: rgba(120, 53, 15, 0.35);
    }
    .log-level-info {
        color: #bae6fd;
    }
    .log-control-panel {
        border: 1px solid #e3e6f0;
        border-radius: .5rem;
        padding: .75rem;
        background: #f8f9fc;
    }
    .log-live-panel {
        border: 1px dashed #cfd6e4;
        border-radius: .5rem;
        padding: .6rem .75rem;
        background: #fff;
    }
    .timer-btn {
        min-width: 56px;
    }
    .timer-icon {
        font-size: 14px;
        margin-right: 4px;
    }
    .pause-chip {
        display: inline-flex;
        align-items: center;
        border: 1px solid #d6dbe8;
        border-radius: 999px;
        padding: .35rem .75rem;
        background: #fff;
        min-height: 38px;
    }
    .pause-chip .form-check {
        margin: 0;
        display: inline-flex;
        align-items: center;
    }
    .pause-chip .form-check-input {
        margin-top: 0;
        margin-right: .45rem;
    }
    .pause-icon {
        margin-right: .4rem;
        color: #6c757d;
        font-size: 13px;
    }
    .log-match-card {
        border: 1px solid #1e293b;
        border-radius: .4rem;
        margin-bottom: .5rem;
        padding: .45rem .6rem;
        background: #020617;
    }
    .log-match-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .5rem;
        margin-bottom: .35rem;
    }
    .log-match-line {
        color: #93c5fd;
        font-size: 12px;
    }
    .log-line-row {
        display: grid;
        grid-template-columns: 56px 1fr;
        align-items: start;
        column-gap: .4rem;
    }
    .log-line-no {
        color: #94a3b8;
        font-size: 12px;
        line-height: 1.7;
        text-align: left;
        user-select: none;
    }
    .target-line-row > div {
        padding-left: 18px;
    }
    .target-line-row {
        border: 1px solid #38bdf8;
        border-radius: 4px;
        background: rgba(14, 165, 233, 0.08);
        padding: 2px 0;
    }
    .log-context-wrap {
        border-top: 1px dashed #334155;
        margin-top: .45rem;
        padding-top: .45rem;
    }
    .log-context-prefix {
        color: #94a3b8;
        font-size: 12px;
        margin-right: .25rem;
    }
    .log-match-card.is-expanded .match-main-row {
        display: none;
    }
</style>
<div class="card shadow mb-4">
    <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">{{ $heading }}</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route(config('log-viewer.route_name_prefix', 'laravel.log.') . 'index') }}" class="mb-3">
            <label class="mb-1 font-weight-bold">???????????? ?? ????</label>
            <div class="input-group">
                <select name="file" class="form-control" onchange="this.form.submit()">
                    @forelse($files as $file)
                        <option value="{{ $file['relative_path'] }}" {{ $selected_file === $file['relative_path'] ? 'selected' : '' }}>
                            {{ $file['label'] }}
                        </option>
                    @empty
                        <option value="">No log files found</option>
                    @endforelse
                </select>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">?????</button>
                </div>
            </div>

            <div class="log-control-panel mt-2">
                <div class="row">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <input type="text" name="q" class="form-control" placeholder="Search in current log..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <select name="level" class="form-control">
                            <option value="ALL" {{ ($level ?? 'ALL') === 'ALL' ? 'selected' : '' }}>All Levels</option>
                            <option value="ERROR" {{ ($level ?? 'ALL') === 'ERROR' ? 'selected' : '' }}>ERROR</option>
                            <option value="WARNING" {{ ($level ?? 'ALL') === 'WARNING' ? 'selected' : '' }}>WARNING</option>
                            <option value="INFO" {{ ($level ?? 'ALL') === 'INFO' ? 'selected' : '' }}>INFO</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <select name="lines" class="form-control">
                            <option value="0" {{ (int)($lines ?? 0) === 0 ? 'selected' : '' }}>No limit</option>
                            <option value="100" {{ (int)($lines ?? 0) === 100 ? 'selected' : '' }}>Last 100</option>
                            <option value="300" {{ (int)($lines ?? 0) === 300 ? 'selected' : '' }}>Last 300</option>
                            <option value="500" {{ (int)($lines ?? 0) === 500 ? 'selected' : '' }}>Last 500</option>
                            <option value="1000" {{ (int)($lines ?? 0) === 1000 ? 'selected' : '' }}>Last 1000</option>
                            <option value="2000" {{ (int)($lines ?? 0) === 2000 ? 'selected' : '' }}>Last 2000</option>
                        </select>
                    </div>
                    @if(($filters_active ?? false))
                    <div class="col-md-2 mb-2 mb-md-0">
                        <select name="context" class="form-control">
                            <option value="0" {{ (int)($context ?? 10) === 0 ? 'selected' : '' }}>No context</option>
                            <option value="2" {{ (int)($context ?? 10) === 2 ? 'selected' : '' }}>Context ±2</option>
                            <option value="5" {{ (int)($context ?? 10) === 5 ? 'selected' : '' }}>Context ±5</option>
                            <option value="10" {{ (int)($context ?? 10) === 10 ? 'selected' : '' }}>Context ±10</option>
                            <option value="20" {{ (int)($context ?? 10) === 20 ? 'selected' : '' }}>Context ±20</option>
                        </select>
                    </div>
                    @else
                    <input type="hidden" name="context" value="{{ (int)($context ?? 10) }}">
                    @endif
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary btn-block" type="submit">Filter</button>
                    </div>
                </div>
            </div>

            <div class="mt-2">
                <a href="{{ route(config('log-viewer.route_name_prefix', 'laravel.log.') . 'index', ['file' => $selected_file, 'lines' => 0, 'context' => 10]) }}" class="btn btn-light border">Reset All Filters</a>
                <a href="{{ route(config('log-viewer.route_name_prefix', 'laravel.log.') . 'download', ['file' => $selected_file, 'q' => ($search ?? ''), 'level' => ($level ?? 'ALL'), 'lines' => ($lines ?? 0)]) }}" class="btn btn-success ml-2">
                    {{ (($search ?? '') === '' && ($level ?? 'ALL') === 'ALL' && (int)($lines ?? 0) === 0) ? 'Download File' : 'Download Filtered View' }}
                </a>
            </div>

            <small class="text-muted">???? ?????????: <code>storage/logs/laravel/YYYY-MM/laravel-YYYY-MM-DD.log</code></small>

            <div class="log-live-panel mt-2">
                <div class="row">
                    <div class="col-md-5 mb-2 mb-md-0">
                        <input type="hidden" id="autoRefreshSeconds" value="0">
                    <div class="btn-group" role="group" aria-label="Auto Refresh Timer">
                        <button type="button" class="btn btn-outline-secondary timer-btn js-timer-btn" data-seconds="0">
                            <span class="timer-icon">?</span>Off
                            </button>
                            <button type="button" class="btn btn-outline-secondary timer-btn js-timer-btn" data-seconds="5">
                                <span class="timer-icon">?</span>5
                            </button>
                            <button type="button" class="btn btn-outline-secondary timer-btn js-timer-btn" data-seconds="10">
                                <span class="timer-icon">?</span>10
                            </button>
                        <button type="button" class="btn btn-outline-secondary timer-btn js-timer-btn" data-seconds="30">
                            <span class="timer-icon">?</span>30
                        </button>
                    </div>
                    <small id="autoRefreshStatus" class="text-muted d-block mt-2"></small>
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <div class="pause-chip">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pauseAtScroll">
                                <label class="form-check-label" for="pauseAtScroll">
                                    <span class="pause-icon">?</span>Pause at current scroll
                                </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
            </div>
        </form>

        <div class="border rounded p-2" style="background:#020617; border-color:#1e293b !important;">
            @if(($filters_active ?? false) && !empty($matched_entries ?? []))
                <div id="logContent" style="max-height: 70vh; overflow:auto;">
                    @foreach($matched_entries as $entry)
                        <div class="log-match-card">
                            <div class="log-match-head">
                                <small class="log-match-line"></small>
                                @if((int)($context ?? 10) > 0)
                                <button type="button" class="btn btn-sm btn-outline-info js-toggle-context" data-target="{{ $entry['id'] }}">
                                    Show ±{{ (int)($context ?? 10) }} lines
                                </button>
                                @endif
                            </div>
                            <div class="log-line-row match-main-row">
                                <span class="log-line-no">L{{ $entry['line_no'] }}</span>
                                <div>{!! $entry['main_html'] !!}</div>
                            </div>
                            @if((int)($context ?? 10) > 0)
                            <div id="{{ $entry['id'] }}" class="log-context-wrap d-none">
                                {!! $entry['before_html'] !!}
                                <div class="log-line-row target-line-row">
                                    <span class="log-line-no">L{{ $entry['line_no'] }}</span>
                                    <div>{!! $entry['main_html'] !!}</div>
                                </div>
                                {!! $entry['after_html'] !!}
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
            <pre id="logContent" style="white-space: pre-wrap; word-break: break-word; max-height: 70vh; overflow:auto; margin:0; color:#f8fafc; font-size:14px; line-height:1.6; font-weight:500; font-family:Consolas, 'Courier New', monospace;">{!! $content_html !!}</pre>
            @endif
        </div>
    </div>
</div>
<script>
    (function () {
        const refreshKey = 'laravel_log_refresh_seconds';
        const pauseKey = 'laravel_log_pause_scroll';
        const scrollKey = 'laravel_log_scroll_top';
        const refreshInput = document.getElementById('autoRefreshSeconds');
        const timerButtons = Array.prototype.slice.call(document.querySelectorAll('.js-timer-btn'));
        const pauseCheckbox = document.getElementById('pauseAtScroll');
        const logContent = document.getElementById('logContent');
        const statusText = document.getElementById('autoRefreshStatus');

        if (!refreshInput || !pauseCheckbox || !logContent || timerButtons.length === 0) {
            return;
        }

        const savedRefresh = localStorage.getItem(refreshKey) || '0';
        const savedPause = localStorage.getItem(pauseKey) === '1';
        const savedScroll = parseInt(localStorage.getItem(scrollKey) || '0', 10);

        refreshInput.value = ['0', '5', '10', '30'].includes(savedRefresh) ? savedRefresh : '0';
        pauseCheckbox.checked = savedPause;
        if (savedPause && savedScroll > 0) {
            logContent.scrollTop = savedScroll;
        }

        const paintTimerButtons = function () {
            const activeSeconds = refreshInput.value;
            timerButtons.forEach(function (btn) {
                const isActive = btn.getAttribute('data-seconds') === activeSeconds;
                btn.classList.toggle('btn-primary', isActive);
                btn.classList.toggle('btn-outline-secondary', !isActive);
            });
        };

        let timerId = null;
        const applyTimer = function () {
            if (timerId) {
                clearInterval(timerId);
                timerId = null;
            }
            const seconds = parseInt(refreshInput.value || '0', 10);
            const paused = pauseCheckbox.checked;
            if (statusText) {
                if (paused && seconds > 0) {
                    statusText.textContent = 'Auto refresh paused';
                } else if (seconds > 0) {
                    statusText.textContent = 'Auto refresh active (' + seconds + 's)';
                } else {
                    statusText.textContent = 'Auto refresh off';
                }
            }
            if (!paused && seconds > 0) {
                timerId = setInterval(function () {
                    window.location.reload();
                }, seconds * 1000);
            }
        };

        timerButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                refreshInput.value = btn.getAttribute('data-seconds') || '0';
                localStorage.setItem(refreshKey, refreshInput.value);
                if (parseInt(refreshInput.value || '0', 10) > 0 && pauseCheckbox.checked) {
                    pauseCheckbox.checked = false;
                    localStorage.setItem(pauseKey, '0');
                    localStorage.removeItem(scrollKey);
                }
                paintTimerButtons();
                applyTimer();
            });
        });

        pauseCheckbox.addEventListener('change', function () {
            localStorage.setItem(pauseKey, pauseCheckbox.checked ? '1' : '0');
            if (!pauseCheckbox.checked) {
                localStorage.removeItem(scrollKey);
            }
            applyTimer();
        });

        logContent.addEventListener('scroll', function () {
            if (pauseCheckbox.checked) {
                localStorage.setItem(scrollKey, String(logContent.scrollTop || 0));
            }
        });

        const contextToggles = Array.prototype.slice.call(document.querySelectorAll('.js-toggle-context'));
        contextToggles.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const targetId = btn.getAttribute('data-target');
                const target = targetId ? document.getElementById(targetId) : null;
                if (!target) {
                    return;
                }
                const isHidden = target.classList.contains('d-none');
                target.classList.toggle('d-none', !isHidden);
                const card = btn.closest('.log-match-card');
                if (card) {
                    card.classList.toggle('is-expanded', isHidden);
                }
                btn.textContent = isHidden
                    ? 'Hide ±{{ (int)($context ?? 10) }} lines'
                    : 'Show ±{{ (int)($context ?? 10) }} lines';
            });
        });

        paintTimerButtons();
        applyTimer();
    })();
</script>
@endsection
