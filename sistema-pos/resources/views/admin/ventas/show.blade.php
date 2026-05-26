<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalle de venta</h2></x-slot>

    <div class="py-4">
        <div style="max-width:1700px;margin:0 auto;padding:0 12px">
            <div class="admin-shell">
                @include('admin.partials.sidebar')

                <div class="admin-content">

                    {{-- Cabecera --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                        <div style="display:flex;align-items:center;gap:12px">
                            <a href="{{ route('admin.ventas') }}" style="font-size:13px;color:#6b7280;text-decoration:none">← Volver</a>
                            <div>
                                <p style="font-size:17px;font-weight:700;color:#111827;margin:0">
                                    Venta <span style="font-family:monospace;color:#6b7280">{{ $venta->numero_venta }}</span>
                                </p>
                                <p style="font-size:12px;color:#9ca3af;margin:2px 0 0">
                                    {{ \Carbon\Carbon::parse($venta->fecha)->format('d \d\e F \d\e Y — H:i') }}
                                </p>
                            </div>
                        </div>
                        <button onclick="window.print()" class="v-btn-print">🖨️ Imprimir recibo</button>
                    </div>

                    {{-- Grid: info + resumen --}}
                    <div id="print-area" style="display:grid;grid-template-columns:1fr 300px;gap:16px;align-items:start">

                        {{-- Columna izquierda --}}
                        <div style="display:flex;flex-direction:column;gap:16px">

                            {{-- Info del cliente --}}
                            <div class="v-panel">
                                <p class="v-section-title">👤 Cliente</p>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:12px">
                                    <div>
                                        <p class="v-detail-label">Nombre</p>
                                        <p class="v-detail-val">{{ $venta->cliente }}</p>
                                    </div>
                                    <div>
                                        <p class="v-detail-label">Correo</p>
                                        <p class="v-detail-val">{{ $venta->email ?: '—' }}</p>
                                    </div>
                                    <div>
                                        <p class="v-detail-label">Canal</p>
                                        <p class="v-detail-val">
                                            {{ $venta->observaciones === 'Venta web' ? '🌐 Tienda en línea' : '🏪 POS presencial' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="v-detail-label">Estado</p>
                                        @php
                                            $estadoMap = [
                                                'pagada'   => ['bg'=>'#dcfce7','color'=>'#15803d','txt'=>'✓ Pagada'],
                                                'pendiente'=> ['bg'=>'#fef3c7','color'=>'#b45309','txt'=>'⏳ Pendiente'],
                                                'anulada'  => ['bg'=>'#fee2e2','color'=>'#b91c1c','txt'=>'✕ Anulada'],
                                            ];
                                            $ec = $estadoMap[$venta->estado] ?? ['bg'=>'#f3f4f6','color'=>'#6b7280','txt'=>ucfirst($venta->estado)];
                                        @endphp
                                        <span style="background:{{ $ec['bg'] }};color:{{ $ec['color'] }};font-size:12px;font-weight:600;padding:3px 12px;border-radius:999px">
                                            {{ $ec['txt'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Productos comprados --}}
                            <div class="v-panel">
                                <p class="v-section-title">🛍️ Productos</p>
                                <table style="width:100%;border-collapse:collapse;margin-top:12px;font-size:13px">
                                    <thead>
                                        <tr style="border-bottom:2px solid #f3f4f6">
                                            <th class="v-th-inner">Producto</th>
                                            <th class="v-th-inner" style="text-align:center">SKU</th>
                                            <th class="v-th-inner" style="text-align:center">Cant.</th>
                                            <th class="v-th-inner" style="text-align:right">Precio unit.</th>
                                            <th class="v-th-inner" style="text-align:right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr style="border-bottom:1px solid #f9fafb">
                                                <td style="padding:11px 8px;font-weight:600;color:#111827">{{ $item->nombre }}</td>
                                                <td style="padding:11px 8px;text-align:center;font-family:monospace;font-size:11px;color:#9ca3af">{{ $item->sku ?? '—' }}</td>
                                                <td style="padding:11px 8px;text-align:center">
                                                    <span style="background:#f3f4f6;color:#374151;font-size:12px;font-weight:600;padding:2px 10px;border-radius:999px">
                                                        {{ $item->cantidad }}
                                                    </span>
                                                </td>
                                                <td style="padding:11px 8px;text-align:right;color:#6b7280">
                                                    ${{ number_format($item->precio_unitario, 2, ',', '.') }}
                                                </td>
                                                <td style="padding:11px 8px;text-align:right;font-weight:700;color:#111827">
                                                    ${{ number_format($item->subtotal, 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Columna derecha: resumen de pago --}}
                        <div style="display:flex;flex-direction:column;gap:14px">

                            {{-- Resumen financiero --}}
                            <div class="v-panel">
                                <p class="v-section-title">💳 Resumen de pago</p>
                                <div style="margin-top:14px;display:flex;flex-direction:column;gap:10px">
                                    <div class="v-row-detail">
                                        <span class="v-row-label">Subtotal (sin IVA)</span>
                                        <span class="v-row-val">${{ number_format($venta->subtotal, 2, ',', '.') }}</span>
                                    </div>
                                    <div class="v-row-detail">
                                        <span class="v-row-label">IVA (19%)</span>
                                        <span class="v-row-val">${{ number_format($venta->impuesto, 2, ',', '.') }}</span>
                                    </div>
                                    @if($venta->descuento > 0)
                                    <div class="v-row-detail">
                                        <span class="v-row-label">Descuento</span>
                                        <span class="v-row-val" style="color:#dc2626">-${{ number_format($venta->descuento, 2, ',', '.') }}</span>
                                    </div>
                                    @endif
                                    <div style="border-top:2px solid #111827;padding-top:10px;display:flex;justify-content:space-between;align-items:center">
                                        <span style="font-size:15px;font-weight:700;color:#111827">TOTAL</span>
                                        <span style="font-size:20px;font-weight:800;color:#111827">${{ number_format($venta->total, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Método de pago --}}
                            <div class="v-panel">
                                <p class="v-section-title">🏦 Método de pago</p>
                                @php
                                    $metodoIcons = [
                                        'tarjeta' => ['icon'=>'💳','label'=>'Tarjeta de crédito/débito','bg'=>'#eff6ff','color'=>'#1d4ed8'],
                                        'pse'     => ['icon'=>'🏛️','label'=>'PSE','bg'=>'#f0fdf4','color'=>'#15803d'],
                                        'nequi'   => ['icon'=>'📱','label'=>'Nequi','bg'=>'#fdf4ff','color'=>'#7e22ce'],
                                        'efectivo'=> ['icon'=>'💵','label'=>'Efectivo','bg'=>'#fefce8','color'=>'#92400e'],
                                    ];
                                    $mi = $metodoIcons[$venta->metodo] ?? ['icon'=>'💰','label'=>ucfirst($venta->metodo),'bg'=>'#f3f4f6','color'=>'#374151'];
                                @endphp
                                <div style="margin-top:12px;background:{{ $mi['bg'] }};border-radius:10px;padding:14px;text-align:center">
                                    <p style="font-size:28px;margin:0 0 4px">{{ $mi['icon'] }}</p>
                                    <p style="font-size:14px;font-weight:600;color:{{ $mi['color'] }};margin:0">{{ $mi['label'] }}</p>
                                    <p style="font-size:20px;font-weight:800;color:{{ $mi['color'] }};margin:6px 0 0">
                                        ${{ number_format($venta->pago_monto ?? $venta->total, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Observaciones --}}
                            @if($venta->observaciones)
                            <div class="v-panel">
                                <p class="v-section-title">📝 Observaciones</p>
                                <p style="font-size:13px;color:#6b7280;margin:10px 0 0">{{ $venta->observaciones }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .admin-shell{display:grid;grid-template-columns:230px 1fr;min-height:78vh;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;background:#fff}
        .admin-content{padding:24px;overflow-y:auto;background:#fcfcfd}
        @media(max-width:1100px){#print-area{grid-template-columns:1fr}}
        @media(max-width:900px){.admin-shell{grid-template-columns:1fr}}

        .v-panel{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px}
        .v-section-title{font-size:13px;font-weight:700;color:#111827;margin:0}
        .v-detail-label{font-size:11px;color:#9ca3af;font-weight:500;text-transform:uppercase;letter-spacing:.05em;margin:0 0 3px}
        .v-detail-val{font-size:13px;color:#111827;font-weight:500;margin:0}
        .v-th-inner{padding:8px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;font-weight:500}
        .v-row-detail{display:flex;justify-content:space-between;align-items:center}
        .v-row-label{font-size:13px;color:#6b7280}
        .v-row-val{font-size:13px;font-weight:600;color:#111827}

        .v-btn-print{padding:9px 18px;background:#fff;color:#374151;font-size:13px;font-weight:600;border-radius:10px;border:1px solid #d1d5db;cursor:pointer}
        .v-btn-print:hover{background:#f9fafb}

        @media print {
            body > *:not(.py-4) { display:none !important; }
            .admin-shell { border:0 !important; grid-template-columns:1fr !important; }
            .pos-sidebar { display:none !important; }
            .admin-content { padding:0 !important; }
            .v-btn-print { display:none !important; }
            a[href*="ventas"] { display:none !important; }
            header { display:none !important; }
            nav { display:none !important; }
            #print-area { grid-template-columns:1fr !important; }
        }
    </style>
</x-app-layout>
