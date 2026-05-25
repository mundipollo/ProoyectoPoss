<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle de producto
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-2">
                    <p><strong>SKU:</strong> {{ $product->sku }}</p>
                    <p><strong>Nombre:</strong> {{ $product->nombre }}</p>
                    <p><strong>Descripción:</strong> {{ $product->descripcion ?: 'Sin descripción' }}</p>
                    <p><strong>Categoría:</strong> {{ $product->category?->nombre }}</p>
                    <p><strong>Marca:</strong> {{ $product->brand?->nombre ?? 'Sin marca' }}</p>
                    <p><strong>Costo:</strong> ${{ number_format($product->costo, 2) }}</p>
                    <p><strong>Precio:</strong> ${{ number_format($product->precio, 2) }}</p>
                    <p><strong>Stock actual:</strong> {{ $product->stock_actual }}</p>
                    <p><strong>Stock mínimo:</strong> {{ $product->stock_minimo }}</p>
                    <p><strong>Estado:</strong> {{ ucfirst($product->estado) }}</p>

                    <div class="pt-4">
                        <a href="{{ route('products.index') }}" class="px-4 py-2 border rounded-md">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
