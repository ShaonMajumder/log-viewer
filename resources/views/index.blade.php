@extends($layout)

@section('content')
<style>
/* Log viewer theme system (3 dark variants) */
.log-viewer-shell{
  --lv-bg:#020617;
  --lv-surface:#0b1220;
  --lv-surface-soft:#121b2d;
  --lv-border:#1e293b;
  --lv-text:#e5edf7;
  --lv-muted:#94a3b8;
  --lv-primary:#4f8cff;
  --lv-primary-text:#dbeafe;
  --lv-level-default:#e2e8f0;
  --lv-level-error:#fecaca;
  --lv-level-error-bg:rgba(127,29,29,.35);
  --lv-level-warning:#fde68a;
  --lv-level-warning-bg:rgba(120,53,15,.35);
  --lv-level-info:#bae6fd;
  --lv-highlight-border:#38bdf8;
  --lv-highlight-bg:rgba(14,165,233,.08);
}

.log-viewer-shell[data-theme="dark-ink"]{
  --lv-bg:#020617;
  --lv-surface:#0b1220;
  --lv-surface-soft:#121b2d;
  --lv-border:#1e293b;
  --lv-text:#e5edf7;
  --lv-muted:#94a3b8;
  --lv-primary:#4f8cff;
  --lv-primary-text:#dbeafe;
}

.log-viewer-shell[data-theme="dark-graphite"]{
  --lv-bg:#0f1115;
  --lv-surface:#161a22;
  --lv-surface-soft:#202531;
  --lv-border:#2d3544;
  --lv-text:#ecf0f5;
  --lv-muted:#9aa5b5;
  --lv-primary:#5ea1ff;
  --lv-primary-text:#e6f1ff;
  --lv-level-error-bg:rgba(127,29,29,.28);
  --lv-level-warning-bg:rgba(120,53,15,.28);
  --lv-highlight-bg:rgba(14,165,233,.12);
}

.log-viewer-shell[data-theme="dark-forest"]{
  --lv-bg:#08110d;
  --lv-surface:#0f1b16;
  --lv-surface-soft:#17261f;
  --lv-border:#294235;
  --lv-text:#e2f6ed;
  --lv-muted:#98b9ab;
  --lv-primary:#43c28b;
  --lv-primary-text:#d2fae8;
  --lv-level-info:#7dd3fc;
  --lv-highlight-border:#2dd4bf;
  --lv-highlight-bg:rgba(45,212,191,.12);
}

.log-viewer-shell .card{ background:var(--lv-surface); border:1px solid var(--lv-border); }
.log-viewer-shell .card-header{ background:var(--lv-surface-soft); border-bottom:1px solid var(--lv-border); }
.log-viewer-shell .card-header h5{ color:var(--lv-primary) !important; }
.log-viewer-shell .card-body{ color:var(--lv-text); }
.log-viewer-shell .form-control,
.log-viewer-shell .custom-select{
  background:var(--lv-surface-soft);
  color:var(--lv-text);
  border-color:var(--lv-border);
}
.log-viewer-shell .form-control:focus,
.log-viewer-shell .custom-select:focus{
  border-color:var(--lv-primary);
  box-shadow:0 0 0 .2rem rgba(79,140,255,.15);
}
.log-viewer-shell .btn-outline-primary{ color:var(--lv-primary); border-color:var(--lv-primary); }
.log-viewer-shell .btn-outline-primary:hover{ background:var(--lv-primary); color:#061322; }
.log-viewer-shell .btn-primary{ background:var(--lv-primary); border-color:var(--lv-primary); color:#071427; font-weight:600; }

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

.log-panel{ background:var(--lv-bg); border-color:var(--lv-border) !important; }
.log-match-card{ border-color:var(--lv-border) !important; background:var(--lv-surface-soft); }
.log-pre{ white-space:pre-wrap;word-break:break-word;max-height:70vh;overflow:auto;margin:0;color:var(--lv-text);font-size:14px;line-height:1.6;font-weight:500;font-family:Consolas,'Courier New',monospace; }

.theme-label{ color:var(--lv-muted); font-size:.85rem; margin-right:.5rem; }
.theme-select{ max-width:260px; }
</style>

<div id="logViewerShell" class="log-viewer-shell" data-theme="dark-ink">
  <div class="card shadow mb-4">
    <div class="card-header py-2 d-flex justify-content-between align-items-center flex-wrap">
      <h5 class="m-0 font-weight-bold text-primary">{{ $heading }}</h5>
      <div class="d-flex align-items-center mt-2 mt-md-0">
        <label for="themePicker" class="theme-label mb-0">Theme</label>
        <select id="themePicker" class="form-control form-control-sm theme-select">
          <option value="dark-ink">Dark Ink</option>
          <option value="dark-graphite">Dark Graphite</option>
          <option value="dark-forest">Dark Forest</option>
        </select>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route(config('log-viewer.route_name_prefix', 'laravel.log.') . 'index') }}" class="mb-3">
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
      </form>

      <div class="border rounded p-2 log-panel">
        @if(($filters_active ?? false) && !empty($matched_entries ?? []))
          <div id="logContent" style="max-height:70vh;overflow:auto;">
            @foreach($matched_entries as $entry)
              <div class="border rounded mb-2 p-2 log-match-card">
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
  const storageKey = 'log_viewer_theme';
  const allowedThemes = ['dark-ink', 'dark-graphite', 'dark-forest'];

  function applyTheme(theme){
    const selected = allowedThemes.indexOf(theme) !== -1 ? theme : 'dark-ink';
    shell.setAttribute('data-theme', selected);
    if (picker) picker.value = selected;
  }

  applyTheme(localStorage.getItem(storageKey) || 'dark-ink');

  if (picker) {
    picker.addEventListener('change', function(){
      const selected = picker.value;
      applyTheme(selected);
      localStorage.setItem(storageKey, selected);
    });
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
      btn.textContent = hidden ? 'Hide context' : 'Show context';
    });
  });
})();
</script>
@endsection
