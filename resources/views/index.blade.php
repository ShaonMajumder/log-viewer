@extends($layout)

@section('content')
<style>
/* Minimal package styles; host app can override as needed */
.log-line { display:block; }
.log-level-default{ color:#e2e8f0; }
.log-level-error{ color:#fecaca; background:rgba(127,29,29,.35); }
.log-level-warning{ color:#fde68a; background:rgba(120,53,15,.35); }
.log-level-info{ color:#bae6fd; }
.log-line-row{ display:grid; grid-template-columns:56px 1fr; column-gap:.4rem; }
.log-line-no{ color:#94a3b8; font-size:12px; line-height:1.7; }
.log-context-wrap{ border-top:1px dashed #334155; margin-top:.45rem; padding-top:.45rem; }
.target-line-row>div{ padding-left:18px; }
.target-line-row{ border:1px solid #38bdf8; border-radius:4px; background:rgba(14,165,233,.08); padding:2px 0; }
.log-match-card.is-expanded .match-main-row{ display:none; }
</style>

<div class="card shadow mb-4">
  <div class="card-header py-2"><h5 class="m-0 font-weight-bold text-primary">{{ $heading }}</h5></div>
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

    <div class="border rounded p-2" style="background:#020617; border-color:#1e293b !important;">
      @if(($filters_active ?? false) && !empty($matched_entries ?? []))
        <div id="logContent" style="max-height:70vh;overflow:auto;">
          @foreach($matched_entries as $entry)
            <div class="border rounded mb-2 p-2 log-match-card" style="border-color:#1e293b !important;">
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
        <pre id="logContent" style="white-space:pre-wrap;word-break:break-word;max-height:70vh;overflow:auto;margin:0;color:#f8fafc;font-size:14px;line-height:1.6;font-weight:500;font-family:Consolas,'Courier New',monospace;">{!! $content_html !!}</pre>
      @endif
    </div>
  </div>
</div>

<script>
(function(){
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
