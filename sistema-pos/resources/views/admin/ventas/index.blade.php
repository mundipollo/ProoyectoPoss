<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Ventas</h2></x-slot>

    <div class="py-4">
        <div style="max-width:1700px;margin:0 auto;padding:0 12px">
            <div class="admin-shell">
                @include('admin.partials.sidebar')

                <div class="admin-content">

                    {{-- Cabecera --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                        <div>
                            <p style="font-size:18px;font-weight:700;color:#111827">Gestión de ventas</p>
                            <p style="font-size:12px;color:#6b7280;margin-top:2px">Historial de todas las transacciones</p>
                        </div>
                    </div>

                    {{-- Tarjetas resumen --}}
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:20px">
                        <div class="v-card">
                            <p class="v-card-label">Total ventas pagadas</p>
                            <p class="v-card-num">{{ number_format($resumen['total_ventas']) }}</p>
                        </div>
                        <div class="v-card">
                            <p class="v-card-label">Ingresos totales</p>
                            <p class="v-card-num" style="color:#16a34a">
                                ${{ number_format($resumen['total_ingresos'], 2, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Filtros --}}
                    <form method="GET" action="{{ route('admin.ventas') }}"
                          style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;align-items:flex-end">
                        <div style="flex:1;min-width:200px">
                            <label class="v-label">Buscar</label>
                            <input type="text" name="q" value="{{ $q }}" placeholder="Número, cliente o correo…"
                                   class="v-input">
                        </div>
                        <div>
                            <label class="v-label">Estado</label>
                            <select name="estado" class="v-input">
                                <option value="">Todos</option>
                                <option value="pagada"   {{ $estado === 'pagada'   ? 'selected' : '' }}>Pagada</option>
                                <option value="pendiente"{{ $estado === 'pendiente'? 'selected' : '' }}>Pendiente</option>
                                <option value="anulada"  {{ $estado === 'anulada'  ? 'selected' : '' }}>Anulada</option>
                            </select>
                        </div>
                        <div>
                            <label class="v-label">Método de pago</label>
                            <select name="metodo" class="v-input">
                                <option value="">Todos</option>
                                <option value="tarjeta" {{ $metodo === 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                                <option value="pse"     {{ $metodo === 'pse'     ? 'selected' : '' }}>PSE</option>
                                <option value="nequi"   {{ $metodo === 'nequi'   ? 'selected' : '' }}>Nequi</option>
                                <option value="efectivo"{{ $metodo === 'efectivo'? 'selected' : '' }}>Efectivo</option>
                            </select>
                        </div>
                        <div style="display:flex;gap:8px">
                            <button type="submit" class="v-btn-dark">Filtrar</button>
                            @if($q || $estado || $metodo)
                                <a href="{{ route('admin.ventas') }}" class="v-btn-ghost">✕ Limpiar</a>
                            @endif
                        </div>
                    </form>

                    {{-- Tabla --}}
                    <div style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden">
                        <table style="width:100%;border-collapse:collapse;font-size:13px">
                            <thead>
                                <tr style="background:#fafafa;border-bottom:1px solid #f3f4f6">
                                    <th class="v-th"># Venta</th>
                                    <th class="v-th">Cliente</th>
                                    <th class="v-th">Correo</th>
                                    <th class="v-th">Productos</th>
                                    <th class="v-th">Método</th>
                                    <th class="v-th">Total</th>
                                    <th class="v-th">Estado</th>
                                    <th class="v-th">Fecha</th>
                                    <th class="v-th">Detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ventas as $v)
                                    <tr class="v-tr">
                                        <td class="v-td">
                                            <span style="font-family:monospace;font-size:12px;color:#6b7280">{{ $v->numero_venta }}</span>
                                        </td>
                                        <td class="v-td" style="font-weight:600">{{ $v->cliente }}</td>
                                        <td class="v-td" style="color:#6b7280;font-size:12px">{{ $v->email ?: '—' }}</td>
                                        <td class="v-td" style="text-align:center">
                                            <span style="display:inline-block;background:#f3f4f6;color:#374151;font-size:11px;font-weight:600;padding:2px 10px;border-radius:999px">
                                                {{ $v->num_productos }} ítem{{ $v->num_productos != 1 ? 's' : '' }}
                                            </span>
                                        </td>
                                        <td class="v-td">
                                            @php
                                                $metodoColors = [
                                                    'tarjeta' => ['bg'=>'#eff6ff','color'=>'#1d4ed8'],
                                                    'pse'     => ['bg'=>'#f0fdf4','color'=>'#15803d'],
                                                    'nequi'   => ['bg'=>'#fdf4ff','color'=>'#7e22ce'],
                                                    'efectivo'=> ['bg'=>'#fefce8','color'=>'#92400e'],
                                                ];
                                                $mc = $metodoColors[$v->metodo] ?? ['bg'=>'#f3f4f6','color'=>'#6b7280'];
                                            @endphp
                                            <span style="background:{{ $mc['bg'] }};color:{{ $mc['color'] }};font-size:11px;font-weight:600;padding:2px 10px;border-radius:999px">
                                                {{ ucfirst($v->metodo) }}
                                            </span>
                                        </td>
                                        <td class="v-td" style="font-weight:700;color:#111827">
                                            ${{ number_format($v->total, 2, ',', '.') }}
                                        </td>
                                        <td class="v-td">
                                            @php
                                                $estadoMap = [
                                                    'pagada'   => ['bg'=>'#dcfce7','color'=>'#15803d','txt'=>'Pagada'],
                                                    'pendiente'=> ['bg'=>'#fef3c7','color'=>'#b45309','txt'=>'Pendiente'],
                                                    'anulada'  => ['bg'=>'#fee2e2','color'=>'#b91c1c','txt'=>'Anulada'],
                                                ];
                                                $ec = $estadoMap[$v->estado] ?? ['bg'=>'#f3f4f6','color'=>'#6b7280','txt'=>ucfirst($v->estado)];
                                            @endphp
                                            <span style="background:{{ $ec['bg'] }};color:{{ $ec['color'] }};font-size:11px;font-weight:600;padding:2px 10px;border-radius:999px">
                                                {{ $ec['txt'] }}
                                            </span>
                                        </td>
                                        <td class="v-td" style="color:#9ca3af;font-size:12px">
                                            {{ \Carbon\Carbon::parse($v->fecha)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="v-td">
                                            <a href="{{ route('admin.ventas.show', $v->id) }}"
                                               style="color:#4f46e5;font-size:12px;font-weight:500;text-decoration:none">
                                                Ver →
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" style="padding:48px;text-align:center;color:#9ca3af">
                                            No se encontraron ventas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div style="padding:12px 16px;border-top:1px solid #f3f4f6">
                            {{ $ventas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .admin-shell{display:grid;grid-template-columns:230px 1fr;min-height:78vh;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;background:#fff}
        .admin-content{padding:24px;overflow-y:auto;background:#fcfcfd}
        @media(max-width:900px){.admin-shell{grid-template-columns:1fr}}

        .v-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px 20px}
        .v-card-label{font-size:11px;font-weight:500;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin:0 0 6px}
        .v-card-num{font-size:26px;font-weight:800;color:#111827;margin:0}

        .v-label{display:block;font-size:11px;font-weight:500;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px}
        .v-input{border:1px solid #d1d5db;border-radius:10px;padding:8px 12px;font-size:13px;background:#fff;color:#111827;outline:none}
        .v-input:focus{border-color:#111827}

        .v-btn-dark{padding:9px 18px;background:#111827;color:#fff;font-size:13px;font-weight:600;border-radius:10px;border:0;cursor:pointer}
        .v-btn-dark:hover{background:#374151}
        .v-btn-ghost{display:inline-flex;align-items:center;padding:9px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;color:#6b7280;text-decoration:none;background:#fff}
        .v-btn-ghost:hover{background:#f9fafb}

        .v-th{padding:10px 16px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;font-weight:500}
        .v-td{padding:13px 16px;border-bottom:1px solid #f9fafb;vertical-align:middle}
        .v-tr:hover td{background:#fafafa}
        .v-tr:last-child td{border-bottom:0}
    </style>
</x-app-layout>
