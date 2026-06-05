<x-app-layout>
    <x-slot name="header">
        <h2 style="font-weight:700;font-size:20px;color:#111827">📦 Nuevo producto</h2>
    </x-slot>

    <div class="py-4">
        <div style="max-width:1700px;margin:0 auto;padding:0 12px">
            <div class="admin-shell">
                @include('admin.partials.sidebar')

                <div class="admin-content">

                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px">
                        <h3 style="font-size:17px;font-weight:700;color:#111827;margin:0">Registrar producto</h3>
                        <a href="{{ route('products.index') }}" style="color:#6b7280;font-size:13px;text-decoration:none">← Volver al inventario</a>
                    </div>

                    @if($errors->any())
                        <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;border-radius:10px;padding:14px 16px;margin-bottom:20px;font-size:13px">
                            <p style="font-weight:700;margin:0 0 6px">Corrige los siguientes errores:</p>
                            <ul style="margin:0;padding-left:18px">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

                            {{-- ── Columna izquierda ──────────────────────── --}}
                            <div style="display:flex;flex-direction:column;gap:16px">

                                {{-- SKU --}}
                                <div>
                                    <label class="prod-label">SKU <span style="color:#dc2626">*</span></label>
                                    <input type="text" name="sku" value="{{ old('sku') }}" required placeholder="Ej. CAM-011" class="prod-input {{ $errors->has('sku') ? 'prod-input-err' : '' }}">
                                    @error('sku')<p class="prod-err">{{ $message }}</p>@enderror
                                </div>

                                {{-- Nombre --}}
                                <div>
                                    <label class="prod-label">Nombre <span style="color:#dc2626">*</span></label>
                                    <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej. Camiseta básica blanca" class="prod-input {{ $errors->has('nombre') ? 'prod-input-err' : '' }}">
                                    @error('nombre')<p class="prod-err">{{ $message }}</p>@enderror
                                </div>

                                {{-- Descripción --}}
                                <div>
                                    <label class="prod-label">Descripción</label>
                                    <textarea name="descripcion" rows="3" placeholder="Descripción opcional..." class="prod-input" style="resize:vertical;font-family:inherit">{{ old('descripcion') }}</textarea>
                                </div>

                                {{-- Categoría --}}
                                <div>
                                    <label class="prod-label">Categoría <span style="color:#dc2626">*</span></label>
                                    <select name="category_id" required class="prod-input {{ $errors->has('category_id') ? 'prod-input-err' : '' }}">
                                        <option value="">Selecciona una categoría</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<p class="prod-err">{{ $message }}</p>@enderror
                                </div>

                                {{-- Marca --}}
                                <div>
                                    <label class="prod-label">Marca</label>
                                    <select name="brand_id" class="prod-input">
                                        <option value="">Sin marca</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- ── Tallas disponibles (solo prendas) ── --}}
                                @php $tallasOld = old('tallas', []); @endphp
                                <div id="tallas-section" style="display:none">
                                    <label class="prod-label">Tallas disponibles</label>
                                    <p style="font-size:11px;color:#9ca3af;margin:0 0 8px">Selecciona las tallas que tendrá este artículo en la tienda</p>
                                    <div style="display:flex;gap:8px;flex-wrap:wrap">
                                        @foreach(['XS','S','M','L','XL'] as $t)
                                            <label style="cursor:pointer">
                                                <input type="checkbox" name="tallas[]" value="{{ $t }}"
                                                       id="talla-{{ $t }}"
                                                       {{ in_array($t, $tallasOld) ? 'checked' : '' }}
                                                       style="position:absolute;opacity:0;width:0;height:0"
                                                       onchange="syncTallaChip(this)">
                                                <span id="chip-{{ $t }}"
                                                      class="talla-chip {{ in_array($t, $tallasOld) ? 'talla-chip-on' : '' }}"
                                                      onclick="document.getElementById('talla-{{ $t }}').click()">
                                                    {{ $t }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('tallas')<p style="font-size:12px;color:#dc2626;margin:6px 0 0">{{ $message }}</p>@enderror
                                </div>

                                {{-- ── Foto del producto ── --}}
                                <div>
                                    <label class="prod-label">Foto del producto</label>
                                    <div id="drop-zone" style="border:2px dashed #d1d5db;border-radius:12px;padding:20px;text-align:center;cursor:pointer;transition:.2s;background:#fafafa"
                                         onclick="document.getElementById('imagen-input').click()"
                                         ondragover="event.preventDefault();this.style.borderColor='#111827';this.style.background='#f3f4f6'"
                                         ondragleave="this.style.borderColor='#d1d5db';this.style.background='#fafafa'"
                                         ondrop="handleDrop(event)">
                                        <div id="drop-placeholder">
                                            <p style="font-size:28px;margin:0">🖼</p>
                                            <p style="font-size:13px;color:#6b7280;margin:6px 0 0">Arrastra una foto aquí o <span style="color:#111827;font-weight:600;text-decoration:underline">haz clic para seleccionar</span></p>
                                            <p style="font-size:11px;color:#9ca3af;margin:4px 0 0">JPG, PNG o WEBP · máx. 3 MB</p>
                                        </div>
                                        <img id="img-preview" src="" alt="Vista previa" style="display:none;max-height:180px;max-width:100%;border-radius:8px;margin:0 auto">
                                    </div>
                                    <input type="file" id="imagen-input" name="imagen" accept="image/jpg,image/jpeg,image/png,image/webp" style="display:none" onchange="previewImage(this)">
                                    @error('imagen')<p class="prod-err">{{ $message }}</p>@enderror
                                </div>

                            </div>

                            {{-- ── Columna derecha ───────────────────────── --}}
                            <div style="display:flex;flex-direction:column;gap:16px">

                                {{-- Precios --}}
                                <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:16px">
                                    <p style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin:0 0 14px">💰 Precios</p>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                                        <div>
                                            <label class="prod-label">Costo <span style="color:#dc2626">*</span></label>
                                            <input type="number" name="costo" value="{{ old('costo', 0) }}" min="0" step="0.01" required class="prod-input">
                                            @error('costo')<p class="prod-err">{{ $message }}</p>@enderror
                                        </div>
                                        <div>
                                            <label class="prod-label">Precio venta <span style="color:#dc2626">*</span></label>
                                            <input type="number" name="precio" value="{{ old('precio', 0) }}" min="0" step="0.01" required class="prod-input">
                                            @error('precio')<p class="prod-err">{{ $message }}</p>@enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Stock --}}
                                <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:16px">
                                    <p style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin:0 0 14px">📦 Stock</p>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                                        <div>
                                            <label class="prod-label">Stock actual <span style="color:#dc2626">*</span></label>
                                            <input type="number" name="stock_actual" value="{{ old('stock_actual', 0) }}" min="0" required class="prod-input">
                                            @error('stock_actual')<p class="prod-err">{{ $message }}</p>@enderror
                                        </div>
                                        <div>
                                            <label class="prod-label">Stock mínimo <span style="color:#dc2626">*</span></label>
                                            <input type="number" name="stock_minimo" value="{{ old('stock_minimo', 5) }}" min="0" required class="prod-input">
                                            @error('stock_minimo')<p class="prod-err">{{ $message }}</p>@enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Estado --}}
                                <div>
                                    <label class="prod-label">Estado</label>
                                    <select name="estado" class="prod-input">
                                        <option value="activo" {{ old('estado', 'activo') === 'activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>

                                {{-- Género --}}
                                <div>
                                    <label class="prod-label">Género</label>
                                    <select name="genero" class="prod-input">
                                        <option value="unisex"  {{ old('genero', 'unisex') === 'unisex'  ? 'selected' : '' }}>Unisex (aparece en ambos)</option>
                                        <option value="hombre"  {{ old('genero') === 'hombre'  ? 'selected' : '' }}>👔 Hombre</option>
                                        <option value="mujer"   {{ old('genero') === 'mujer'   ? 'selected' : '' }}>👗 Mujer</option>
                                    </select>
                                </div>

                                {{-- Botones --}}
                                <div style="display:flex;gap:10px;margin-top:8px">
                                    <button type="submit" class="prod-btn-save">Guardar producto</button>
                                    <a href="{{ route('products.index') }}" class="prod-btn-cancel">Cancelar</a>
                                </div>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <style>
        .admin-shell{display:grid;grid-template-columns:230px 1fr;min-height:78vh;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;background:#fff}
        .admin-content{padding:24px;overflow-y:auto;background:#fcfcfd}
        .prod-label{display:block;font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px}
        .prod-input{width:100%;border:1px solid #d1d5db;border-radius:10px;font-size:14px;padding:10px 12px;background:#fff;box-sizing:border-box;outline:none;transition:.15s}
        .prod-input:focus{border-color:#111827;box-shadow:0 0 0 2px rgba(17,24,39,.08)}
        .prod-input-err{border-color:#fca5a5}
        .prod-err{font-size:12px;color:#dc2626;margin:4px 0 0}
        .prod-btn-save{flex:1;border:0;border-radius:10px;background:#111827;color:#fff;padding:12px;font-size:14px;font-weight:700;cursor:pointer;transition:.2s}
        .prod-btn-save:hover{background:#1f2937}
        .prod-btn-cancel{flex:1;border:1px solid #e5e7eb;border-radius:10px;background:#fff;color:#374151;padding:12px;font-size:14px;font-weight:600;text-decoration:none;text-align:center;transition:.2s}
        .prod-btn-cancel:hover{background:#f9fafb}
        @media(max-width:1000px){.admin-shell{grid-template-columns:1fr}}
        @media(max-width:700px){form>div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important}}
        /* Chips de talla */
        .talla-chip{display:inline-block;padding:7px 14px;border:2px solid #d1d5db;border-radius:8px;font-size:13px;font-weight:700;color:#6b7280;cursor:pointer;transition:.15s;user-select:none}
        .talla-chip:hover{border-color:#6b7280;color:#111827}
        .talla-chip-on{border-color:#111827;background:#111827;color:#fff}
    </style>

    <script>
    const CAT_CON_TALLAS = ['Camisetas','Pantalones','Vestidos','Chaquetas','Ropa deportiva'];

    function toggleTallas() {
        const sel = document.getElementById('category_id');
        const cat = sel ? sel.options[sel.selectedIndex]?.text?.trim() : '';
        const sec = document.getElementById('tallas-section');
        if (sec) sec.style.display = CAT_CON_TALLAS.includes(cat) ? 'block' : 'none';
    }

    function syncTallaChip(input) {
        const chip = document.getElementById('chip-' + input.value);
        if (chip) chip.classList.toggle('talla-chip-on', input.checked);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const sel = document.getElementById('category_id');
        if (sel) { sel.addEventListener('change', toggleTallas); toggleTallas(); }
    });
    </script>
    <script>
    function previewImage(input) {
        const preview = document.getElementById('img-preview');
        const placeholder = document.getElementById('drop-placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function handleDrop(e) {
        e.preventDefault();
        const dz = document.getElementById('drop-zone');
        dz.style.borderColor = '#d1d5db';
        dz.style.background = '#fafafa';
        const file = e.dataTransfer.files[0];
        if (!file) return;
        const input = document.getElementById('imagen-input');
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        previewImage(input);
    }
    </script>
</x-app-layout>
