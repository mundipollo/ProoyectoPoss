<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventario</h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-[1700px] mx-auto px-3 sm:px-5 lg:px-8">
            <div class="admin-shell">
                @include('admin.partials.sidebar')

                <div class="admin-content">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-semibold text-gray-800">Productos</h3>
                        <a href="{{ route('products.create') }}" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-lg hover:bg-gray-700 transition">
                            + Nuevo producto
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-800 text-sm border border-green-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b border-gray-100 text-xs uppercase tracking-wider text-gray-400">
                                    <th class="py-3 px-4">SKU</th>
                                    <th class="py-3 px-4">Nombre</th>
                                    <th class="py-3 px-4">Categoría</th>
                                    <th class="py-3 px-4">Precio</th>
                                    <th class="py-3 px-4">Stock</th>
                                    <th class="py-3 px-4">Estado</th>
                                    <th class="py-3 px-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                        <td class="py-3 px-4 text-gray-500 text-xs">{{ $product->sku }}</td>
                                        <td class="py-3 px-4 font-medium">{{ $product->nombre }}</td>
                                        <td class="py-3 px-4 text-gray-600">{{ $product->category->nombre }}</td>
                                        <td class="py-3 px-4">${{ number_format($product->precio, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4">
                                            <span class="{{ $product->stock_actual <= $product->stock_minimo ? 'text-red-600 font-semibold' : '' }}">
                                                {{ $product->stock_actual }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded-full text-xs {{ $product->estado === 'activo' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                                {{ ucfirst($product->estado) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 space-x-3">
                                            <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:underline">Ver</a>
                                            <a href="{{ route('products.edit', $product) }}" class="text-amber-600 hover:underline">Editar</a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('¿Eliminar producto?')">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-10 text-center text-gray-400">No hay productos registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="p-4">{{ $products->links() }}</div>
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
