@extends($layout)

@section('content')
<style>
.log-viewer-shell{
  --lv-bg:#020617; --lv-surface:#0b1220; --lv-surface-soft:#121b2d; --lv-border:#1e293b;
  --lv-text:#e5edf7; --lv-muted:#94a3b8; --lv-primary:#4f8cff;
  --lv-level-default:#e2e8f0; --lv-level-error:#fecaca; --lv-level-error-bg:rgba(127,29,29,.35);
  --lv-level-warning:#fde68a; --lv-level-warning-bg:rgba(120,53,15,.35); --lv-level-info:#bae6fd;
  --lv-highlight-border:#38bdf8; --lv-highlight-bg:rgba(14,165,233,.08);
}
.log-viewer-shell[data-theme="ink-dark"]{ --lv-bg:#020617; --lv-surface:#0b1220; --lv-surface-soft:#121b2d; --lv-border:#1e293b; --lv-text:#e5edf7; --lv-muted:#94a3b8; --lv-primary:#4f8cff; }
.log-viewer-shell[data-theme="ink-light"]{ --lv-bg:#f7f9fc; --lv-surface:#fff; --lv-surface-soft:#edf2fb; --lv-border:#c8d4ea; --lv-text:#172033; --lv-muted:#5b6b86; --lv-primary:#2f66de; --lv-level-default:#22324f; --lv-level-error:#991b1b; --lv-level-error-bg:rgba(220,38,38,.10); --lv-level-warning:#92400e; --lv-level-warning-bg:rgba(245,158,11,.16); --lv-level-info:#0c4a6e; --lv-highlight-border:#0284c7; --lv-highlight-bg:rgba(14,165,233,.10); }
.log-viewer-shell[data-theme="graphite-dark"]{ --lv-bg:#0f1115; --lv-surface:#161a22; --lv-surface-soft:#202531; --lv-border:#2d3544; --lv-text:#ecf0f5; --lv-muted:#9aa5b5; --lv-primary:#5ea1ff; --lv-level-error-bg:rgba(127,29,29,.28); --lv-level-warning-bg:rgba(120,53,15,.28); --lv-highlight-bg:rgba(14,165,233,.12); }
.log-viewer-shell[data-theme="graphite-light"]{ --lv-bg:#f4f5f7; --lv-surface:#fff; --lv-surface-soft:#eceff3; --lv-border:#c6ced9; --lv-text:#1f2733; --lv-muted:#647284; --lv-primary:#2563eb; --lv-level-default:#2b3748; --lv-level-error:#991b1b; --lv-level-error-bg:rgba(220,38,38,.10); --lv-level-warning:#854d0e; --lv-level-warning-bg:rgba(234,179,8,.16); --lv-level-info:#155e75; --lv-highlight-border:#0284c7; --lv-highlight-bg:rgba(56,189,248,.11); }
.log-viewer-shell[data-theme="forest-dark"]{ --lv-bg:#08110d; --lv-surface:#0f1b16; --lv-surface-soft:#17261f; --lv-border:#294235; --lv-text:#e2f6ed; --lv-muted:#98b9ab; --lv-primary:#43c28b; --lv-level-info:#7dd3fc; --lv-highlight-border:#2dd4bf; --lv-highlight-bg:rgba(45,212,191,.12); }
.log-viewer-shell[data-theme="forest-light"]{ --lv-bg:#f3faf6; --lv-surface:#fff; --lv-surface-soft:#e8f5ed; --lv-border:#b8d8c5; --lv-text:#173525; --lv-muted:#5f8b73; --lv-primary:#15803d; --lv-level-default:#18412d; --lv-level-error:#7f1d1d; --lv-level-error-bg:rgba(239,68,68,.12); --lv-level-warning:#854d0e; --lv-level-warning-bg:rgba(250,204,21,.18); --lv-level-info:#0e7490; --lv-highlight-border:#0d9488; --lv-highlight-bg:rgba(20,184,166,.12); }
.log-viewer-shell .card{ background:var(--lv-surface); border:1px solid var(--lv-border); }
.log-viewer-shell .card-header,.log-control-panel{ background:var(--lv-surface-soft); }
.log-viewer-shell .card-header{ border-bottom:1px solid var(--lv-border); }
.log-viewer-shell .card-header h5,.log-viewer-shell .text-primary{ color:var(--lv-primary)!important; }
.log-viewer-shell .card-body{ color:var(--lv-text); }
.log-viewer-shell .form-control{ background:var(--lv-surface-soft); color:var(--lv-text); border-color:var(--lv-border); }
.log-viewer-shell .btn-outline-primary{ color:var(--lv-primary); border-color:var(--lv-primary); }
.log-viewer-shell .btn-outline-primary:hover{ background:var(--lv-primary); color:#061322; }
.log-viewer-shell .btn-primary{ background:var(--lv-primary); border-color:var(--lv-primary); color:#071427; font-weight:600; }
.log-control-panel{ border:1px solid var(--lv-border); border-radius:.5rem; padding:.75rem; }
.log-live-panel{ border:1px dashed var(--lv-border); border-radius:.5rem; padding:.6rem .75rem; background:var(--lv-surface); }
.timer-btn{ min-width:56px; }
.pause-chip{ display:inline-flex; align-items:center; border:1px solid var(--lv-border); border-radius:999px; padding:.35rem .75rem; background:var(--lv-surface); min-height:38px; }
.log-line { display:block; }
.log-level-default{ color:var(--lv-level-default); }
.log-level-error{ color:var(--lv-level-error); background:var(--lv-level-error-bg); }
.log-level-warning{ color:var(--lv-level-warning); background:var(--lv-level-warning-bg); }
.log-level-info{ color:var(--lv-level-info); }
.log-line-row{ display:grid; grid-template-columns:56px 1fr; column-gap:.4rem; }
.log-line-no{ color:var(--lv-muted); font-size:12px; line-height:1.7; }
.log-context-wrap{ border-top:1px dashed var(--lv-border); margin-top:.45rem; padding-top:.45rem; }
.target-line-row>div{ padding-left:18px; }
.target-line-row{ border:1px solid var(--lv-highlight-border); border-radius:4px; background:var(--lv-highlight-bg); padding:2px 0; }
.log-match-card.is-expanded .match-main-row{ display:none; }
.log-panel{ background:var(--lv-bg); border-color:var(--lv-border)!important; }
.log-match-card{ border:1px solid var(--lv-border); border-radius:.4rem; margin-bottom:.5rem; padding:.45rem .6rem; background:var(--lv-surface-soft); }
.log-pre{ white-space:pre-wrap;word-break:break-word;max-height:70vh;overflow:auto;margin:0;color:var(--lv-text);font-size:14px;line-height:1.6;font-weight:500;font-family:Consolas,'Courier New',monospace; }
.theme-select{ max-width:170px; }
.mode-toggle{
  margin-left:.5rem;
  width:64px;
  height:38px;
  padding:0 8px;
  border-radius:999px;
  display:inline-flex;
  align-items:center;
  justify-content:space-between;
  font-size:15px;
  line-height:1;
}
.mode-toggle .mode-icon{
  opacity:.45;
  transform:scale(.95);
  transition:opacity .2s ease, transform .2s ease;
}
.mode-toggle[data-mode="dark"] .icon-moon,
.mode-toggle[data-mode="light"] .icon-sun{
  opacity:1;
  transform:scale(1.05);
}
</style>

<div id="logViewerShell" class="log-viewer-shell" data-theme="ink-dark">
  <div class="card shadow mb-4">
    <div class="card-header py-2 d-flex justify-content-between align-items-center flex-wrap">
      <h5 class="m-0 font-weight-bold text-primary">{{ $heading }}</h5>
      <div class="d-flex align-items-center mt-2 mt-md-0">
        <label for="themePicker" class="mb-0 mr-2" style="color:var(--lv-muted)">Theme</label>
        <select id="themePicker" class="form-control form-control-sm theme-select">
          <option value="ink">Ink</option><option value="graphite">Graphite</option><option value="forest">Forest</option>
        </select>
        <button type="button" id="modeToggle" class="btn btn-sm btn-outline-primary mode-toggle" title="Switch theme mode" aria-label="Switch theme mode" data-mode="dark"><span class="mode-icon icon-sun">☀️</span><span class="mode-icon icon-moon">🌙</span></button>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route(config('log-viewer.route_name_prefix', 'log.viewer.') . 'index') }}" class="mb-3">
        <div class="input-group mb-2">
          <select name="file" class="form-control" onchange="this.form.submit()">
            @forelse($files as $file)
              <option value="{{ $file['relative_path'] }}" {{ $selected_file === $file['relative_path'] ? 'selected' : '' }}>{{ $file['label'] }}</option>
            @empty
              <option value="">No log files found</option>
            @endforelse
          </select>
          <div class="input-group-append"><button class="btn btn-primary" type="submit">View</button></div>
        </div>

        <div class="log-control-panel mt-2">
          <div class="form-row">
            <div class="col-md-4 mb-2"><input type="text" name="q" class="form-control" placeholder="Search in current log..." value="{{ $search ?? '' }}"></div>
            <div class="col-md-2 mb-2"><select name="level" class="form-control"><option value="ALL" {{ ($level ?? 'ALL') === 'ALL' ? 'selected' : '' }}>All Levels</option><option value="ERROR" {{ ($level ?? 'ALL') === 'ERROR' ? 'selected' : '' }}>ERROR</option><option value="WARNING" {{ ($level ?? 'ALL') === 'WARNING' ? 'selected' : '' }}>WARNING</option><option value="INFO" {{ ($level ?? 'ALL') === 'INFO' ? 'selected' : '' }}>INFO</option></select></div>
            <div class="col-md-2 mb-2"><select name="lines" class="form-control"><option value="0" {{ (int)($lines ?? 0) === 0 ? 'selected' : '' }}>No limit</option><option value="100" {{ (int)($lines ?? 0) === 100 ? 'selected' : '' }}>Last 100</option><option value="300" {{ (int)($lines ?? 0) === 300 ? 'selected' : '' }}>Last 300</option><option value="500" {{ (int)($lines ?? 0) === 500 ? 'selected' : '' }}>Last 500</option><option value="1000" {{ (int)($lines ?? 0) === 1000 ? 'selected' : '' }}>Last 1000</option><option value="2000" {{ (int)($lines ?? 0) === 2000 ? 'selected' : '' }}>Last 2000</option></select></div>
            @if(($filters_active ?? false))
              <div class="col-md-2 mb-2"><select name="context" class="form-control"><option value="0" {{ (int)($context ?? 10) === 0 ? 'selected' : '' }}>No context</option><option value="2" {{ (int)($context ?? 10) === 2 ? 'selected' : '' }}>Context ±2</option><option value="5" {{ (int)($context ?? 10) === 5 ? 'selected' : '' }}>Context ±5</option><option value="10" {{ (int)($context ?? 10) === 10 ? 'selected' : '' }}>Context ±10</option><option value="20" {{ (int)($context ?? 10) === 20 ? 'selected' : '' }}>Context ±20</option></select></div>
            @else
              <input type="hidden" name="context" value="{{ (int)($context ?? 10) }}">
            @endif
            <div class="col-md-2 mb-2"><button class="btn btn-outline-primary btn-block" type="submit">Filter</button></div>
          </div>
        </div>

        <div class="mt-2">
          <a href="{{ route(config('log-viewer.route_name_prefix', 'log.viewer.') . 'index', ['file' => $selected_file, 'lines' => 0, 'context' => 10]) }}" class="btn btn-light border">Reset All Filters</a>
          <a href="{{ route(config('log-viewer.route_name_prefix', 'log.viewer.') . 'download', ['file' => $selected_file, 'q' => ($search ?? ''), 'level' => ($level ?? 'ALL'), 'lines' => ($lines ?? 0)]) }}" class="btn btn-success ml-2">
            {{ (($search ?? '') === '' && ($level ?? 'ALL') === 'ALL' && (int)($lines ?? 0) === 0) ? 'Download File' : 'Download Filtered View' }}
          </a>
        </div>

        <small style="color:var(--lv-muted)">Log pattern: <code>storage/logs/laravel/YYYY-MM/laravel-YYYY-MM-DD.log</code></small>

        <div class="log-live-panel mt-2">
          <div class="row">
            <div class="col-md-5 mb-2 mb-md-0">
              <input type="hidden" id="autoRefreshSeconds" value="0">
              <div class="btn-group" role="group" aria-label="Auto Refresh Timer">
                <button type="button" class="btn btn-outline-secondary timer-btn js-timer-btn" data-seconds="0">⏱ Off</button>
                <button type="button" class="btn btn-outline-secondary timer-btn js-timer-btn" data-seconds="5">⏱ 5</button>
                <button type="button" class="btn btn-outline-secondary timer-btn js-timer-btn" data-seconds="10">⏱ 10</button>
                <button type="button" class="btn btn-outline-secondary timer-btn js-timer-btn" data-seconds="30">⏱ 30</button>
              </div>
              <small id="autoRefreshStatus" style="color:var(--lv-muted)" class="d-block mt-2"></small>
            </div>
            <div class="col-md-4 d-flex align-items-center">
              <div class="pause-chip">
                <div class="form-check m-0 d-inline-flex align-items-center">
                  <input class="form-check-input mr-2" type="checkbox" id="pauseAtScroll">
                  <label class="form-check-label" for="pauseAtScroll">Pause at current scroll</label>
                </div>
              </div>
            </div>
            <div class="col-md-3"></div>
          </div>
        </div>
      </form>

      <div class="border rounded p-2 log-panel">
        @if(($filters_active ?? false) && !empty($matched_entries ?? []))
          <div id="logContent" style="max-height:70vh;overflow:auto;">
            @foreach($matched_entries as $entry)
              <div class="log-match-card">
                @if((int)($context ?? 10) > 0)
                  <div class="text-right mb-2"><button type="button" class="btn btn-sm btn-outline-info js-toggle-context" data-target="{{ $entry['id'] }}">Show ±{{ (int)($context ?? 10) }} lines</button></div>
                @endif
                <div class="log-line-row match-main-row"><span class="log-line-no">L{{ $entry['line_no'] }}</span><div>{!! $entry['main_html'] !!}</div></div>
                @if((int)($context ?? 10) > 0)
                  <div id="{{ $entry['id'] }}" class="log-context-wrap d-none">{!! $entry['before_html'] !!}<div class="log-line-row target-line-row"><span class="log-line-no">L{{ $entry['line_no'] }}</span><div>{!! $entry['main_html'] !!}</div></div>{!! $entry['after_html'] !!}</div>
                @endif
              </div>
            @endforeach
          </div>
        @else
          <pre id="logContent" class="log-pre">{!! $content_html !!}</pre>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const shell = document.getElementById('logViewerShell');
  const picker = document.getElementById('themePicker');
  const modeToggle = document.getElementById('modeToggle');
  const themeStorageKey = 'log_viewer_theme_family';
  const modeStorageKey = 'log_viewer_theme_mode';
  const themes = ['ink','graphite','forest'];

  const refreshKey = 'laravel_log_refresh_seconds';
  const pauseKey = 'laravel_log_pause_scroll';
  const scrollKey = 'laravel_log_scroll_top';
  const refreshInput = document.getElementById('autoRefreshSeconds');
  const timerButtons = Array.prototype.slice.call(document.querySelectorAll('.js-timer-btn'));
  const pauseCheckbox = document.getElementById('pauseAtScroll');
  const logContent = document.getElementById('logContent');
  const statusText = document.getElementById('autoRefreshStatus');

  function normFamily(v){ return themes.indexOf(v) !== -1 ? v : 'ink'; }
  function normMode(v){ return v === 'light' ? 'light' : 'dark'; }
  function applyTheme(f,m){
    const family = normFamily(f), mode = normMode(m);
    shell.setAttribute('data-theme', family + '-' + mode);
    picker.value = family;
    modeToggle.setAttribute('data-mode', mode);
    modeToggle.setAttribute('title', mode === 'dark' ? 'Dark mode active' : 'Light mode active');
    localStorage.setItem(themeStorageKey, family);
    localStorage.setItem(modeStorageKey, mode);
  }
  applyTheme(localStorage.getItem(themeStorageKey) || 'ink', localStorage.getItem(modeStorageKey) || 'dark');
  picker.addEventListener('change', () => applyTheme(picker.value, localStorage.getItem(modeStorageKey) || 'dark'));
  modeToggle.addEventListener('click', () => {
    const currentMode = normMode(localStorage.getItem(modeStorageKey) || 'dark');
    applyTheme(localStorage.getItem(themeStorageKey) || 'ink', currentMode === 'dark' ? 'light' : 'dark');
  });

  if (refreshInput && pauseCheckbox && logContent && timerButtons.length) {
    const savedRefresh = localStorage.getItem(refreshKey) || '0';
    const savedPause = localStorage.getItem(pauseKey) === '1';
    const savedScroll = parseInt(localStorage.getItem(scrollKey) || '0', 10);
    refreshInput.value = ['0','5','10','30'].includes(savedRefresh) ? savedRefresh : '0';
    pauseCheckbox.checked = savedPause;
    if (savedPause && savedScroll > 0) logContent.scrollTop = savedScroll;

    const paintTimerButtons = function(){
      const active = refreshInput.value;
      timerButtons.forEach(btn => {
        const on = btn.getAttribute('data-seconds') === active;
        btn.classList.toggle('btn-primary', on);
        btn.classList.toggle('btn-outline-secondary', !on);
      });
    };

    let timerId = null;
    const applyTimer = function(){
      if (timerId) clearInterval(timerId);
      timerId = null;
      const seconds = parseInt(refreshInput.value || '0', 10);
      const paused = pauseCheckbox.checked;
      if (statusText) {
        if (paused && seconds > 0) statusText.textContent = 'Auto refresh paused';
        else if (seconds > 0) statusText.textContent = 'Auto refresh active (' + seconds + 's)';
        else statusText.textContent = 'Auto refresh off';
      }
      if (!paused && seconds > 0) timerId = setInterval(() => window.location.reload(), seconds * 1000);
    };

    timerButtons.forEach(btn => btn.addEventListener('click', function(){
      refreshInput.value = btn.getAttribute('data-seconds') || '0';
      localStorage.setItem(refreshKey, refreshInput.value);
      if (parseInt(refreshInput.value || '0', 10) > 0 && pauseCheckbox.checked) {
        pauseCheckbox.checked = false;
        localStorage.setItem(pauseKey, '0');
        localStorage.removeItem(scrollKey);
      }
      paintTimerButtons();
      applyTimer();
    }));

    pauseCheckbox.addEventListener('change', function(){
      localStorage.setItem(pauseKey, pauseCheckbox.checked ? '1' : '0');
      if (!pauseCheckbox.checked) localStorage.removeItem(scrollKey);
      applyTimer();
    });

    logContent.addEventListener('scroll', function(){
      if (pauseCheckbox.checked) localStorage.setItem(scrollKey, String(logContent.scrollTop || 0));
    });

    paintTimerButtons();
    applyTimer();
  }

  const toggles = Array.prototype.slice.call(document.querySelectorAll('.js-toggle-context'));
  toggles.forEach(function(btn){
    btn.addEventListener('click', function(){
      const target = document.getElementById(btn.getAttribute('data-target'));
      if (!target) return;
      const hidden = target.classList.contains('d-none');
      target.classList.toggle('d-none', !hidden);
      const card = btn.closest('.log-match-card');
      if (card) card.classList.toggle('is-expanded', hidden);
      btn.textContent = hidden ? 'Hide ±{{ (int)($context ?? 10) }} lines' : 'Show ±{{ (int)($context ?? 10) }} lines';
    });
  });
})();
</script>
@endsection
