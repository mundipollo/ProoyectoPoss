@csrf

<div>
    <x-input-label for="sku" :value="__('SKU')" />
    <x-text-input id="sku" name="sku" type="text" class="mt-1 block w-full" :value="old('sku', $product->sku ?? '')" required />
    <x-input-error class="mt-2" :messages="$errors->get('sku')" />
</div>

<div class="mt-4">
    <x-input-label for="nombre" :value="__('Nombre')" />
    <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $product->nombre ?? '')" required />
    <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
</div>

<div class="mt-4">
    <x-input-label for="descripcion" :value="__('Descripción')" />
    <textarea id="descripcion" name="descripcion" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('descripcion', $product->descripcion ?? '') }}</textarea>
    <x-input-error class="mt-2" :messages="$errors->get('descripcion')" />
</div>

<div class="mt-4">
    <x-input-label for="category_id" :value="__('Categoría')" />
    <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        <option value="">Seleccione una categoría</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                {{ $category->nombre }}
            </option>
        @endforeach
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
</div>

<div class="mt-4">
    <x-input-label for="brand_id" :value="__('Marca')" />
    <select id="brand_id" name="brand_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        <option value="">Sin marca</option>
        @foreach ($brands as $brand)
            <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id ?? '') == $brand->id)>
                {{ $brand->nombre }}
            </option>
        @endforeach
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('brand_id')" />
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
    <div>
        <x-input-label for="costo" :value="__('Costo')" />
        <x-text-input id="costo" name="costo" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('costo', $product->costo ?? 0)" required />
    </div>
    <div>
        <x-input-label for="precio" :value="__('Precio')" />
        <x-text-input id="precio" name="precio" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('precio', $product->precio ?? 0)" required />
    </div>
    <div>
        <x-input-label for="stock_actual" :value="__('Stock actual')" />
        <x-text-input id="stock_actual" name="stock_actual" type="number" min="0" class="mt-1 block w-full" :value="old('stock_actual', $product->stock_actual ?? 0)" required />
    </div>
    <div>
        <x-input-label for="stock_minimo" :value="__('Stock mínimo')" />
        <x-text-input id="stock_minimo" name="stock_minimo" type="number" min="0" class="mt-1 block w-full" :value="old('stock_minimo', $product->stock_minimo ?? 0)" required />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="estado" :value="__('Estado')" />
    <select id="estado" name="estado" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        <option value="activo" @selected(old('estado', $product->estado ?? 'activo') === 'activo')>Activo</option>
        <option value="inactivo" @selected(old('estado', $product->estado ?? 'activo') === 'inactivo')>Inactivo</option>
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('estado')" />
</div>
