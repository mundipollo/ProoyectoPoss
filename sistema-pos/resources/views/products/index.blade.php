<x-app-layout>
    <x-slot name="header">
        <h2 style="font-weight:700;font-size:20px;color:#111827">Inventario</h2>
    </x-slot>

    <div class="py-4">
        <div style="max-width:1700px;margin:0 auto;padding:0 12px">
            <div class="admin-shell">
                @include('admin.partials.sidebar')

                <div class="admin-content">

                    {{-- Encabezado + botón --}}
                    <div class="inv-header">
                        <h3 style="font-size:17px;font-weight:700;color:#111827;margin:0">Productos</h3>
                        <a href="{{ route('products.create') }}" class="inv-btn-new">+ Nuevo producto</a>
                    </div>

                    {{-- ── Filtro de género (pills) ──────────────────────── --}}
                    <div class="inv-pills">
                        @php
                            $baseParams = array_filter(['q'=>$q,'categoria'=>$categoria]);
                        @endphp
                        <a href="{{ route('products.index', $baseParams) }}"
                           class="inv-pill {{ !in_array($genero,['hombre','mujer']) ? 'inv-pill-on' : '' }}">
                            Todos
                        </a>
                        <a href="{{ route('products.index', array_merge($baseParams,['genero'=>'hombre'])) }}"
                           class="inv-pill {{ $genero==='hombre' ? 'inv-pill-on' : '' }}">
                            👔 Hombre
                        </a>
                        <a href="{{ route('products.index', array_merge($baseParams,['genero'=>'mujer'])) }}"
                           class="inv-pill {{ $genero==='mujer' ? 'inv-pill-on' : '' }}">
                            👗 Mujer
                        </a>
                    </div>

                    {{-- ── Buscador + categoría ──────────────────────────── --}}
                    <form method="GET" action="{{ route('products.index') }}" class="inv-search-form">
                        @if($genero)<input type="hidden" name="genero" value="{{ $genero }}">@endif

                        <input type="text" name="q" value="{{ $q }}"
                               placeholder="Buscar por nombre o SKU…"
                               class="inv-input inv-input-grow">

                        <select name="categoria" class="inv-input">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $categoria === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="inv-btn-search">Filtrar</button>

                        @if($q || $categoria || in_array($genero,['hombre','mujer']))
                            <a href="{{ route('products.index') }}" class="inv-btn-clear">✕ Limpiar</a>
                        @endif
                    </form>

                    {{-- Conteo de resultados --}}
                    @if($q || $categoria || in_array($genero,['hombre','mujer']))
                        <p style="font-size:12px;color:#6b7280;margin-bottom:12px">
                            {{ $products->total() }} producto{{ $products->total()!=1?'s':'' }} encontrado{{ $products->total()!=1?'s':'' }}
                            @if($q) · búsqueda <strong style="color:#111827">"{{ $q }}"</strong> @endif
                            @if($categoria) · categoría <strong style="color:#111827">{{ $categoria }}</strong> @endif
                            @if(in_array($genero,['hombre','mujer'])) · género <strong style="color:#111827">{{ ucfirst($genero) }}</strong> @endif
                        </p>
                    @endif

                    {{-- Mensaje de éxito --}}
                    @if(session('status'))
                        <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600">
                            ✅ {{ session('status') }}
                        </div>
                    @endif

                    {{-- ── Tabla ─────────────────────────────────────────── --}}
                    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden">
                        <div style="overflow-x:auto">
                            <table style="width:100%;border-collapse:collapse;font-size:13px;min-width:640px">
                                <thead>
                                    <tr style="border-bottom:2px solid #f3f4f6">
                                        <th class="inv-th">SKU</th>
                                        <th class="inv-th">Nombre</th>
                                        <th class="inv-th">Categoría</th>
                                        <th class="inv-th">Género</th>
                                        <th class="inv-th">Precio</th>
                                        <th class="inv-th">Stock</th>
                                        <th class="inv-th">Estado</th>
                                        <th class="inv-th">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr class="inv-tr">
                                            <td class="inv-td" style="font-family:monospace;font-size:11px;color:#6b7280">{{ $product->sku }}</td>
                                            <td class="inv-td" style="font-weight:600;color:#111827">{{ $product->nombre }}</td>
                                            <td class="inv-td" style="color:#6b7280">{{ $product->category->nombre ?? '—' }}</td>
                                            <td class="inv-td">
                                                @php
                                                    $gi = ['hombre'=>'👔','mujer'=>'👗','unisex'=>'🔄'];
                                                    $gc = ['hombre'=>'#eff6ff','mujer'=>'#fdf4ff','unisex'=>'#f3f4f6'];
                                                    $gt = ['hombre'=>'#1d4ed8','mujer'=>'#7e22ce','unisex'=>'#6b7280'];
                                                    $g  = $product->genero ?? 'unisex';
                                                @endphp
                                                <span style="background:{{ $gc[$g]??'#f3f4f6' }};color:{{ $gt[$g]??'#6b7280' }};padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600">
                                                    {{ $gi[$g]??'' }} {{ ucfirst($g) }}
                                                </span>
                                            </td>
                                            <td class="inv-td" style="font-weight:600">${{ number_format($product->precio, 0, ',', '.') }}</td>
                                            <td class="inv-td">
                                                <span style="font-weight:600;color:{{ $product->stock_actual <= $product->stock_minimo ? '#dc2626' : '#374151' }}">
                                                    {{ $product->stock_actual }}
                                                </span>
                                                @if($product->stock_actual <= $product->stock_minimo)
                                                    <span style="font-size:10px;color:#dc2626;margin-left:3px">⚠</span>
                                                @endif
                                            </td>
                                            <td class="inv-td">
                                                @if($product->estado === 'activo')
                                                    <span style="background:#dcfce7;color:#15803d;padding:3px 9px;border-radius:999px;font-size:11px;font-weight:600">Activo</span>
                                                @else
                                                    <span style="background:#f3f4f6;color:#6b7280;padding:3px 9px;border-radius:999px;font-size:11px;font-weight:600">Inactivo</span>
                                                @endif
                                            </td>
                                            <td class="inv-td">
                                                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                                                    <a href="{{ route('products.show', $product) }}" style="color:#6366f1;font-size:12px;text-decoration:none;font-weight:600">Ver</a>
                                                    <a href="{{ route('products.edit', $product) }}" style="color:#d97706;font-size:12px;text-decoration:none;font-weight:600">Editar</a>
                                                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;margin:0">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            style="color:#ef4444;font-size:12px;background:none;border:none;cursor:pointer;padding:0;font-weight:600"
                                                            onclick="return confirm('¿Eliminar {{ addslashes($product->nombre) }}?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" style="padding:40px;text-align:center;color:#9ca3af;font-size:13px">
                                                @if($q || $categoria || $genero)
                                                    No hay productos con esos filtros.
                                                    <a href="{{ route('products.index') }}" style="color:#111827;font-weight:700;margin-left:6px">Limpiar filtros</a>
                                                @else
                                                    No hay productos registrados.
                                                    <a href="{{ route('products.create') }}" style="color:#111827;font-weight:700;margin-left:6px">Agregar el primero →</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div style="padding:14px 16px;border-top:1px solid #f3f4f6">
                            {{ $products->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        /* Layout */
        .admin-shell{display:grid;grid-template-columns:230px 1fr;min-height:78vh;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;background:#fff}
        .admin-content{padding:24px;overflow-y:auto;background:#fcfcfd}

        /* Encabezado */
        .inv-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px}
        .inv-btn-new{background:#111827;color:#fff;padding:9px 16px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;transition:.2s;white-space:nowrap}
        .inv-btn-new:hover{background:#1f2937}

        /* Pills de género */
        .inv-pills{display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap}
        .inv-pill{padding:7px 18px;border-radius:999px;font-size:13px;font-weight:600;text-decoration:none;border:2px solid #d1d5db;background:#fff;color:#374151;transition:.2s;white-space:nowrap}
        .inv-pill:hover{border-color:#6b7280;color:#111827}
        .inv-pill-on{border-color:#111827;background:#111827;color:#fff}
        .inv-pill-on:hover{border-color:#111827;background:#1f2937;color:#fff}

        /* Buscador + filtros */
        .inv-search-form{display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap;align-items:center}
        .inv-input{border:1px solid #d1d5db;border-radius:10px;padding:9px 12px;font-size:13px;background:#fff;outline:none;transition:.15s;min-width:0}
        .inv-input:focus{border-color:#111827;box-shadow:0 0 0 2px rgba(17,24,39,.08)}
        .inv-input-grow{flex:1;min-width:160px}
        .inv-btn-search{padding:9px 18px;background:#111827;color:#fff;font-size:13px;font-weight:600;border-radius:10px;border:0;cursor:pointer;white-space:nowrap;transition:.2s}
        .inv-btn-search:hover{background:#374151}
        .inv-btn-clear{padding:9px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;color:#6b7280;text-decoration:none;white-space:nowrap;transition:.2s}
        .inv-btn-clear:hover{background:#f9fafb}

        /* Tabla */
        .inv-th{text-align:left;font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;padding:10px 14px;white-space:nowrap}
        .inv-td{padding:11px 14px;border-bottom:1px solid #f9fafb;vertical-align:middle}
        .inv-tr:last-child .inv-td{border-bottom:0}
        .inv-tr:hover .inv-td{background:#f9fafb}

        /* Responsive */
        @media(max-width:900px){
            .admin-shell{grid-template-columns:1fr}
        }
        @media(max-width:600px){
            .admin-content{padding:16px}
            .inv-search-form{flex-direction:column;align-items:stretch}
            .inv-input,.inv-btn-search,.inv-btn-clear{width:100%}
        }
    </style>
</x-app-layout>
