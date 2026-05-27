@php
    // ── Métricas del sistema ────────────────────────────────────────
    $respuestaMs = round((microtime(true) - LARAVEL_START) * 1000);
    $memoriaUsada = round(memory_get_usage() / 1024 / 1024, 1);

    // Estado de respuesta
    if ($respuestaMs < 200)      { $respColor = '#16a34a'; $respLabel = 'Rápido'; }
    elseif ($respuestaMs < 600)  { $respColor = '#b45309'; $respLabel = 'Normal'; }
    else                         { $respColor = '#dc2626'; $respLabel = 'Lento';  }

    // Estado de memoria
    $limiteMB = (int) ini_get('memory_limit');
    $memPct   = $limiteMB > 0 ? round(($memoriaUsada / $limiteMB) * 100) : 0;
    if ($memPct < 50)      { $memColor = '#16a34a'; }
    elseif ($memPct < 80)  { $memColor = '#b45309'; }
    else                   { $memColor = '#dc2626'; }

    $queryCount = count(\Illuminate\Support\Facades\DB::getQueryLog());
@endphp

<style>
.pos-sidebar{background:#f7f7f8;border-right:1px solid #e5e7eb;padding:18px 14px;display:flex;flex-direction:column;justify-content:space-between}
.pos-brand{font-size:14px;font-weight:700;color:#111827}
.pos-subtitle{font-size:12px;color:#6b7280;margin-top:2px}
.pos-nav{margin-top:16px;display:flex;flex-direction:column;gap:4px}
.pos-nav-item{padding:9px 10px;border-radius:10px;font-size:13px;color:#374151;transition:.2s;text-decoration:none;display:block}
.pos-nav-item:hover{background:#eceef1}
.pos-nav-item.is-active{background:#111827;color:#fff;font-weight:600}
.pos-sidebar-footer{margin-top:auto;padding-top:12px;border-top:1px solid #e5e7eb;font-size:11px;display:grid;gap:6px;color:#9ca3af}
.pos-logout-btn{width:100%;border:0;background:0;text-align:left;padding:9px 10px;border-radius:10px;font-size:13px;color:#ef4444;cursor:pointer;transition:.2s}
.pos-logout-btn:hover{background:#fef2f2}

/* Widget telemetría */
.tele-box{margin-top:16px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px}
.tele-title{font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;margin:0 0 8px}
.tele-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px}
.tele-row:last-child{margin-bottom:0}
.tele-label{font-size:11px;color:#6b7280}
.tele-val{font-size:11px;font-weight:700;font-family:monospace}
.tele-bar-bg{height:4px;background:#f3f4f6;border-radius:99px;margin-top:2px;overflow:hidden}
.tele-bar{height:4px;border-radius:99px;transition:.3s}
</style>

<aside class="pos-sidebar">
    <div style="flex:1;overflow-y:auto">
        <p class="pos-brand">Facturación POSS</p>
        <p class="pos-subtitle">Panel de empleado</p>

        <nav class="pos-nav">
            <a href="{{ route('employer.dashboard') }}" class="pos-nav-item {{ request()->routeIs('employer.dashboard') ? 'is-active' : '' }}">📊 Dashboard</a>
            <a href="{{ route('employer.pos') }}"       class="pos-nav-item {{ request()->routeIs('employer.pos')       ? 'is-active' : '' }}">⚡ Facturar</a>
            <a href="{{ route('employer.clientes.create') }}" class="pos-nav-item {{ request()->routeIs('employer.clientes.*') ? 'is-active' : '' }}">👤 Registrar cliente</a>
        </nav>

        {{-- ── Widget de telemetría ────────────────────────────────── --}}
        <div class="tele-box">
            <p class="tele-title">📡 Estado del sistema</p>

            {{-- Respuesta --}}
            <div class="tele-row">
                <span class="tele-label">Respuesta</span>
                <span class="tele-val" style="color:{{ $respColor }}">{{ $respuestaMs }} ms &nbsp;·&nbsp; {{ $respLabel }}</span>
            </div>
            <div class="tele-bar-bg" style="margin-bottom:7px">
                <div class="tele-bar" style="width:{{ min(100, round($respuestaMs/10)) }}%;background:{{ $respColor }}"></div>
            </div>

            {{-- Memoria --}}
            <div class="tele-row">
                <span class="tele-label">Memoria RAM</span>
                <span class="tele-val" style="color:{{ $memColor }}">{{ $memoriaUsada }} MB</span>
            </div>
            <div class="tele-bar-bg" style="margin-bottom:7px">
                <div class="tele-bar" style="width:{{ $memPct }}%;background:{{ $memColor }}"></div>
            </div>

            {{-- Base de datos --}}
            <div class="tele-row">
                <span class="tele-label">Base de datos</span>
                <span class="tele-val" style="color:#6b7280">{{ $queryCount }} {{ $queryCount === 1 ? 'query' : 'queries' }}</span>
            </div>

            {{-- Indicador general --}}
            <div style="margin-top:8px;padding-top:8px;border-top:1px solid #f3f4f6;display:flex;align-items:center;gap:6px">
                @if($respuestaMs < 600 && $memPct < 80)
                    <span style="width:8px;height:8px;border-radius:50%;background:#16a34a;display:inline-block"></span>
                    <span style="font-size:11px;color:#16a34a;font-weight:600">Sistema operando bien</span>
                @elseif($respuestaMs < 1200 && $memPct < 90)
                    <span style="width:8px;height:8px;border-radius:50%;background:#b45309;display:inline-block"></span>
                    <span style="font-size:11px;color:#b45309;font-weight:600">Carga moderada</span>
                @else
                    <span style="width:8px;height:8px;border-radius:50%;background:#dc2626;display:inline-block;animation:blink 1s infinite"></span>
                    <span style="font-size:11px;color:#dc2626;font-weight:600">Sobrecargado</span>
                @endif
            </div>
        </div>
    </div>

    <div class="pos-sidebar-footer">
        <p>{{ Auth::user()->name }}</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="pos-logout-btn">↩ Cerrar sesión</button>
        </form>
    </div>
</aside>

<style>
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
</style>
