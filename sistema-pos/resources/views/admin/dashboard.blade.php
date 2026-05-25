<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-[1700px] mx-auto px-3 sm:px-5 lg:px-8">
            <div class="admin-shell">
                @include('admin.partials.sidebar')

                <div class="admin-content">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white shadow-sm rounded-xl p-5 border border-gray-100">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Ventas hoy</p>
                            <p class="text-2xl font-semibold mt-1">${{ number_format($sales['today_total'], 0, ',', '.') }} <span class="text-sm font-normal text-gray-400">COP</span></p>
                        </div>
                        <div class="bg-white shadow-sm rounded-xl p-5 border border-gray-100">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Ventas del mes</p>
                            <p class="text-2xl font-semibold mt-1">${{ number_format($sales['month_total'], 0, ',', '.') }} <span class="text-sm font-normal text-gray-400">COP</span></p>
                        </div>
                        <div class="bg-white shadow-sm rounded-xl p-5 border border-gray-100">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Ticket promedio (hoy)</p>
                            <p class="text-2xl font-semibold mt-1">${{ number_format($sales['ticket_promedio_hoy'], 0, ',', '.') }} <span class="text-sm font-normal text-gray-400">COP</span></p>
                        </div>
                        <div class="bg-white shadow-sm rounded-xl p-5 border border-gray-100">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Transacciones hoy</p>
                            <p class="text-2xl font-semibold mt-1">{{ $sales['ventas_hoy'] }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                        <div class="bg-white shadow-sm rounded-xl p-4 border border-gray-100 text-center">
                            <p class="text-xs text-gray-500">Productos</p>
                            <p class="text-3xl font-semibold mt-1">{{ $stats['products'] }}</p>
                        </div>
                        <div class="bg-white shadow-sm rounded-xl p-4 border border-gray-100 text-center">
                            <p class="text-xs text-gray-500">Categorías</p>
                            <p class="text-3xl font-semibold mt-1">{{ $stats['categories'] }}</p>
                        </div>
                        <div class="bg-white shadow-sm rounded-xl p-4 border border-gray-100 text-center">
                            <p class="text-xs text-gray-500">Marcas</p>
                            <p class="text-3xl font-semibold mt-1">{{ $stats['brands'] }}</p>
                        </div>
                        <div class="bg-white shadow-sm rounded-xl p-4 border border-gray-100 text-center">
                            <p class="text-xs text-gray-500">Clientes web</p>
                            <p class="text-3xl font-semibold mt-1">{{ $stats['customers'] }}</p>
                        </div>
                        <div class="bg-white shadow-sm rounded-xl p-4 border border-gray-100 text-center">
                            <p class="text-xs text-gray-500">Ventas totales</p>
                            <p class="text-3xl font-semibold mt-1">{{ $stats['sales'] }}</p>
                        </div>
                        <div class="bg-white shadow-sm rounded-xl p-4 border border-gray-100 text-center">
                            <p class="text-xs text-gray-500">Bajo stock</p>
                            <p class="text-3xl font-semibold mt-1 {{ count($lowStockProducts) > 0 ? 'text-red-600' : '' }}">{{ count($lowStockProducts) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                            <p class="text-sm font-semibold text-gray-700 mb-4">Productos más vendidos</p>
                            @if (count($topProducts) === 0)
                                <p class="text-sm text-gray-400">Aún no hay ventas registradas.</p>
                            @else
                                <div class="space-y-3">
                                    @foreach ($topProducts as $item)
                                        <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                                            <div>
                                                <p class="font-medium text-gray-900 text-sm">{{ $item['nombre'] }}</p>
                                                <p class="text-xs text-gray-400">{{ $item['unidades_vendidas'] }} uds. vendidas</p>
                                            </div>
                                            <p class="text-sm font-semibold">${{ number_format($item['total_vendido'], 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                            <p class="text-sm font-semibold text-gray-700 mb-4">Bajo stock</p>
                            @if (count($lowStockProducts) === 0)
                                <p class="text-sm text-gray-400">Sin alertas de bajo stock.</p>
                            @else
                                <div class="space-y-3">
                                    @foreach ($lowStockProducts as $item)
                                        <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                                            <div>
                                                <p class="font-medium text-gray-900 text-sm">{{ $item['nombre'] }}</p>
                                                <p class="text-xs text-gray-400">SKU: {{ $item['sku'] }}</p>
                                            </div>
                                            <p class="text-sm font-semibold text-red-500">{{ $item['stock_actual'] }} / mín {{ $item['stock_minimo'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                            <p class="text-sm font-semibold text-gray-700 mb-4">Ventas recientes</p>
                            @if (count($recentSales) === 0)
                                <p class="text-sm text-gray-400">No hay ventas recientes.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm">
                                        <thead class="text-left text-gray-400 border-b text-xs uppercase tracking-wider">
                                            <tr>
                                                <th class="py-2 pr-4">Referencia</th>
                                                <th class="py-2 pr-4">Cliente</th>
                                                <th class="py-2 pr-4">Total</th>
                                                <th class="py-2">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recentSales as $sale)
                                                <tr class="border-b border-gray-50">
                                                    <td class="py-2 pr-4 font-medium text-xs">{{ $sale['numero_venta'] }}</td>
                                                    <td class="py-2 pr-4 text-gray-600">{{ $sale['cliente'] }}</td>
                                                    <td class="py-2 pr-4 font-semibold">${{ number_format($sale['total'], 0, ',', '.') }}</td>
                                                    <td class="py-2">
                                                        <span class="px-2 py-0.5 rounded-full text-xs {{ $sale['estado'] === 'pagada' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                                            {{ ucfirst($sale['estado']) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                            <p class="text-sm font-semibold text-gray-700 mb-4">Métodos de pago</p>
                            @if (count($paymentMethods) === 0)
                                <p class="text-sm text-gray-400">No hay pagos registrados.</p>
                            @else
                                <div class="space-y-3">
                                    @foreach ($paymentMethods as $payment)
                                        <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                                            <div>
                                                <p class="font-medium text-gray-900 text-sm uppercase">{{ $payment['metodo'] }}</p>
                                                <p class="text-xs text-gray-400">{{ $payment['cantidad'] }} transacciones</p>
                                            </div>
                                            <p class="text-sm font-semibold">${{ number_format($payment['total'], 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
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
        @media(max-width:900px){.admin-shell{grid-template-columns:1fr}.pos-sidebar{border-right:0;border-bottom:1px solid #e5e7eb}}
    </style>
</x-app-layout>
