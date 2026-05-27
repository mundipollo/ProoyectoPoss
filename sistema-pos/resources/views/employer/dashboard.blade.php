<x-app-layout>
    <x-slot name="header">
        <h2 style="font-weight:700;font-size:20px;color:#111827">Panel de empleado</h2>
    </x-slot>

    <div class="py-4">
        <div style="max-width:1400px;margin:0 auto;padding:0 20px">
            <div class="emp-shell">
                @include('employer.partials.sidebar')

                <main class="emp-main">

                    {{-- ── Tarjetas de resumen ─────────────────────────── --}}
                    <div class="emp-cards">

                        <div class="emp-card">
                            <p class="emp-card-label">Ventas hoy</p>
                            <p class="emp-card-value">{{ $ventasHoy['cantidad'] }}</p>
                            <p class="emp-card-sub">{{ number_format($ventasHoy['total'], 0, ',', '.') }} COP</p>
                        </div>

                        <div class="emp-card emp-card-green">
                            <p class="emp-card-label">Ingresos hoy</p>
                            <p class="emp-card-value" style="color:#16a34a">$ {{ number_format($ventasHoy['total'], 0, ',', '.') }}</p>
                            <p class="emp-card-sub">COP</p>
                        </div>

                        <div class="emp-card">
                            <p class="emp-card-label">Ventas del mes</p>
                            <p class="emp-card-value">{{ $ventasMes['cantidad'] }}</p>
                            <p class="emp-card-sub">{{ number_format($ventasMes['total'], 0, ',', '.') }} COP</p>
                        </div>

                        <div class="emp-card emp-card-blue">
                            <p class="emp-card-label">Ingresos del mes</p>
                            <p class="emp-card-value" style="color:#1d4ed8">$ {{ number_format($ventasMes['total'], 0, ',', '.') }}</p>
                            <p class="emp-card-sub">COP</p>
                        </div>

                    </div>

                    {{-- ── Gráfica de barras últimos 7 días ────────────── --}}
                    <div class="emp-section">
                        <h3 class="emp-section-title">📈 Ventas últimos 7 días</h3>
                        <div class="emp-chart">
                            @php $maxTotal = max(1, collect($porDia)->max('total')); @endphp
                            @foreach ($porDia as $dia)
                                @php $pct = round(($dia['total'] / $maxTotal) * 100); @endphp
                                <div class="emp-bar-col">
                                    <span class="emp-bar-val">
                                        @if($dia['total'] > 0)
                                            ${{ number_format($dia['total'] / 1000, 0, ',', '.') }}k
                                        @else
                                            –
                                        @endif
                                    </span>
                                    <div class="emp-bar-wrap">
                                        <div class="emp-bar-fill" style="height:{{ $pct }}%"></div>
                                    </div>
                                    <span class="emp-bar-label">{{ $dia['dia'] }}</span>
                                    <span class="emp-bar-qty">{{ $dia['cantidad'] }} vta{{ $dia['cantidad'] !== 1 ? 's' : '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Ventas recientes ─────────────────────────────── --}}
                    <div class="emp-section">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                            <h3 class="emp-section-title" style="margin:0">🧾 Ventas recientes</h3>
                            <a href="{{ route('employer.pos') }}" class="emp-btn-primary">+ Nueva venta</a>
                        </div>

                        @if(count($recientes))
                            <div style="overflow-x:auto">
                                <table class="emp-table">
                                    <thead>
                                        <tr>
                                            <th># Venta</th>
                                            <th>Vendedor</th>
                                            <th>Método</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recientes as $v)
                                            <tr>
                                                <td style="font-family:monospace;font-weight:600;color:#111827">{{ $v['numero_venta'] }}</td>
                                                <td>{{ $v['vendedor'] }}</td>
                                                <td>
                                                    @php
                                                        $mIcons = ['tarjeta'=>'💳','pse'=>'🏦','nequi'=>'📱'];
                                                        $mIcon  = $mIcons[$v['metodo']] ?? '💰';
                                                    @endphp
                                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px">
                                                        {{ $mIcon }} {{ ucfirst($v['metodo']) }}
                                                    </span>
                                                </td>
                                                <td style="font-weight:600">$ {{ number_format($v['total'], 0, ',', '.') }} COP</td>
                                                <td>
                                                    @if($v['estado'] === 'pagada')
                                                        <span class="emp-badge emp-badge-green">Pagada</span>
                                                    @elseif($v['estado'] === 'pendiente')
                                                        <span class="emp-badge emp-badge-yellow">Pendiente</span>
                                                    @else
                                                        <span class="emp-badge emp-badge-gray">{{ ucfirst($v['estado']) }}</span>
                                                    @endif
                                                </td>
                                                <td style="font-size:12px;color:#6b7280">{{ \Carbon\Carbon::parse($v['fecha'])->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="emp-empty">No hay ventas registradas aún. <a href="{{ route('employer.pos') }}" style="color:#111827;font-weight:600">¡Realiza la primera!</a></div>
                        @endif
                    </div>

                </main>
            </div>
        </div>
    </div>

    <style>
        /* Layout */
        .emp-shell{display:grid;grid-template-columns:230px 1fr;min-height:78vh;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;background:#fff}
        .emp-main{padding:24px;background:#fcfcfd;overflow-y:auto}

        /* Tarjetas */
        .emp-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px}
        .emp-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px 18px}
        .emp-card-green{border-left:4px solid #16a34a}
        .emp-card-blue{border-left:4px solid #1d4ed8}
        .emp-card-label{font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin:0 0 6px}
        .emp-card-value{font-size:26px;font-weight:800;color:#111827;margin:0}
        .emp-card-sub{font-size:11px;color:#6b7280;margin:4px 0 0}

        /* Sección */
        .emp-section{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:18px 20px;margin-bottom:20px}
        .emp-section-title{font-size:15px;font-weight:700;color:#111827;margin:0 0 14px}

        /* Gráfica de barras */
        .emp-chart{display:flex;align-items:flex-end;gap:8px;height:160px;padding-bottom:4px}
        .emp-bar-col{display:flex;flex-direction:column;align-items:center;flex:1;height:100%}
        .emp-bar-val{font-size:10px;color:#6b7280;margin-bottom:4px;white-space:nowrap}
        .emp-bar-wrap{flex:1;width:100%;background:#f3f4f6;border-radius:6px;overflow:hidden;display:flex;align-items:flex-end}
        .emp-bar-fill{width:100%;background:#111827;border-radius:6px 6px 0 0;min-height:4px;transition:.3s}
        .emp-bar-label{font-size:10px;color:#374151;margin-top:5px;font-weight:600}
        .emp-bar-qty{font-size:9px;color:#9ca3af;margin-top:1px}

        /* Tabla */
        .emp-table{width:100%;border-collapse:collapse;font-size:13px}
        .emp-table thead th{text-align:left;font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;padding:8px 12px;border-bottom:2px solid #f3f4f6;white-space:nowrap}
        .emp-table tbody td{padding:10px 12px;border-bottom:1px solid #f9fafb;color:#374151;vertical-align:middle}
        .emp-table tbody tr:last-child td{border-bottom:0}
        .emp-table tbody tr:hover{background:#f9fafb}

        /* Badges */
        .emp-badge{display:inline-block;padding:3px 9px;border-radius:999px;font-size:11px;font-weight:600}
        .emp-badge-green{background:#dcfce7;color:#15803d}
        .emp-badge-yellow{background:#fef9c3;color:#a16207}
        .emp-badge-gray{background:#f3f4f6;color:#6b7280}

        /* Botón */
        .emp-btn-primary{background:#111827;color:#fff;padding:7px 14px;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;transition:.2s}
        .emp-btn-primary:hover{background:#1f2937}

        /* Vacío */
        .emp-empty{text-align:center;padding:28px;color:#9ca3af;font-size:13px;border:1px dashed #e5e7eb;border-radius:10px}

        @media(max-width:1100px){.emp-cards{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:800px){.emp-shell{grid-template-columns:1fr}.emp-cards{grid-template-columns:1fr 1fr}}
    </style>
</x-app-layout>
