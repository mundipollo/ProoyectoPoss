@extends('store.layout')

@section('title', 'Carrito')

@section('content')
    <section class="store-hero">
        <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=2000" alt="Carrito Poss Atelier" class="store-hero-bg">
        <div class="store-hero-overlay"></div>
        <div class="store-hero-content store-hero-content--cart">
            @include('store.partials.hero-heading', [
                'title' => 'Carrito',
                'subtitle' => 'Revisa tu pedido y completa el pago',
            ])
            <a href="{{ route('store.catalog') }}" class="hero-cart-link pill-link text-sm px-5 py-2.5 rounded-full border transition">
                ← Seguir comprando
            </a>
        </div>
    </section>

    <section class="px-6 md:px-12 lg:px-20 py-12 md:py-20 reveal">
        @php
            $hasCart = $lines->isNotEmpty();
            $hasOrder = ! empty($lastOrder);
        @endphp

        <nav class="checkout-tabs" aria-label="Pasos de compra" id="checkout-tabs">
            <button type="button" class="checkout-tab {{ $activeTab === 'carrito' ? 'is-active' : '' }}" data-tab="carrito">
                1. Carrito
            </button>
            <button type="button" class="checkout-tab {{ $activeTab === 'pago' ? 'is-active' : '' }}" data-tab="pago" {{ $hasCart ? '' : 'disabled' }}>
                2. Pago
            </button>
            <button type="button" class="checkout-tab {{ $activeTab === 'confirmacion' ? 'is-active' : '' }}" data-tab="confirmacion" {{ $hasOrder ? '' : 'disabled' }}>
                3. Confirmación
            </button>
        </nav>

        {{-- Pestaña: Carrito --}}
        <div class="checkout-panel {{ $activeTab === 'carrito' ? 'is-active' : '' }}" data-panel="carrito">
            @if (! $hasCart)
                <div class="text-center py-20 border border-neutral-200 rounded-2xl scroll-card">
                    <p class="text-neutral-600 text-lg mb-6">Tu carrito está vacío.</p>
                    <a href="{{ route('store.catalog') }}" class="pill-btn pill-btn-primary px-8 py-3">
                        Ver productos
                    </a>
                </div>
            @else
                <div class="scroll-card border border-neutral-200 rounded-2xl overflow-hidden overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="border-b border-neutral-200">
                            <tr class="text-left text-neutral-500 uppercase text-xs tracking-widest">
                                <th class="py-4 px-5 font-medium">Producto</th>
                                <th class="py-4 px-5 font-medium">Precio</th>
                                <th class="py-4 px-5 font-medium">Cantidad</th>
                                <th class="py-4 px-5 font-medium">Subtotal</th>
                                <th class="py-4 px-5"></th>
                            </tr>
                        </thead>
                        <tbody id="cart-tbody">
                            @foreach ($lines as $line)
                                <tr class="border-b border-neutral-100 last:border-0" data-row="{{ $line->product->id }}">
                                    <td class="py-5 px-5">
                                        <p class="font-medium text-base">{{ $line->product->nombre }}</p>
                                        <p class="text-xs text-neutral-500 mt-1">{{ $line->product->sku }} · {{ $line->product->category->nombre }}</p>
                                        @if(!empty($line->talla))
                                            <span style="display:inline-block;margin-top:5px;padding:2px 10px;border:1.5px solid #111827;border-radius:6px;font-size:11px;font-weight:700;color:#111827;letter-spacing:.04em">
                                                Talla {{ $line->talla }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-5 px-5 font-medium">${{ number_format($line->product->precio, 0, ',', '.') }}</td>
                                    <td class="py-5 px-5">
                                        <form method="POST" action="{{ route('store.cart.update', $line->product) }}" class="flex items-center gap-2 js-update-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $line->quantity }}" min="1" max="{{ $line->product->stock_actual }}" class="w-16 rounded-full border border-neutral-300 px-2 py-1.5 text-sm text-center">
                                            <button type="submit" class="text-xs text-neutral-600 hover:text-black underline underline-offset-2">Actualizar</button>
                                        </form>
                                    </td>
                                    <td class="py-5 px-5 font-medium text-lg" id="subtotal-{{ $line->product->id }}">${{ number_format($line->subtotal, 0, ',', '.') }}</td>
                                    <td class="py-5 px-5 text-right">
                                        <form method="POST" action="{{ route('store.cart.remove', $line->product) }}" class="js-remove-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-neutral-500 hover:text-black underline underline-offset-2">Quitar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 scroll-card flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-8 rounded-2xl border border-neutral-200">
                    <p class="text-2xl md:text-3xl font-medium tracking-tight">
                        Total: <span class="text-neutral-900" id="cart-total-display">${{ number_format($total, 0, ',', '.') }}</span>
                        <span class="text-sm text-neutral-500 font-normal">COP</span>
                    </p>
                    <button type="button" class="pill-btn pill-btn-primary px-8 py-3 text-sm" data-go-tab="pago">
                        Continuar al pago →
                    </button>
                </div>
            @endif
        </div>

        {{-- Pestaña: Pago (simulación) --}}
        <div class="checkout-panel {{ $activeTab === 'pago' ? 'is-active' : '' }}" data-panel="pago">
            @if ($hasCart)
                <div class="grid lg:grid-cols-5 gap-8">
                    <div class="lg:col-span-2 scroll-card border border-neutral-200 rounded-2xl p-6 md:p-8">
                        <h2 class="text-xl font-medium tracking-tight mb-4">Resumen del pedido</h2>
                        <ul class="space-y-3 text-sm border-b border-neutral-100 pb-4 mb-4">
                            @foreach ($lines as $line)
                                <li class="flex justify-between gap-4">
                                    <span class="text-neutral-600">{{ $line->product->nombre }} × {{ $line->quantity }}</span>
                                    <span class="font-medium shrink-0">${{ number_format($line->subtotal, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <p class="text-2xl font-medium tracking-tight">
                            Total: ${{ number_format($total, 0, ',', '.') }} <span class="text-sm text-neutral-500 font-normal">COP</span>
                        </p>
                        <p class="text-xs text-neutral-500 mt-3">Simulación de pasarela — no se realizará un cobro real.</p>
                    </div>

                    <div class="lg:col-span-3 scroll-card border border-neutral-200 rounded-2xl p-6 md:p-8">
                        <h2 class="text-xl font-medium tracking-tight mb-6">Datos de pago</h2>

                        <form method="POST" action="{{ route('store.checkout.pay') }}" id="payment-form" class="space-y-5">
                            @csrf

                            <div>
                                <p class="text-xs uppercase tracking-widest text-neutral-500 mb-3">Método de pago</p>
                                <div class="payment-method">
                                    <label class="payment-method-option">
                                        <input type="radio" name="payment_method" value="tarjeta" checked>
                                        <span>Tarjeta</span>
                                    </label>
                                    <label class="payment-method-option">
                                        <input type="radio" name="payment_method" value="pse">
                                        <span>PSE</span>
                                    </label>
                                    <label class="payment-method-option">
                                        <input type="radio" name="payment_method" value="nequi">
                                        <span>Nequi</span>
                                    </label>
                                </div>
                                @error('payment_method') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-xs uppercase tracking-widest text-neutral-500 mb-2 block" for="card_name">Nombre en la tarjeta</label>
                                <input id="card_name" type="text" name="card_name" value="{{ old('card_name', auth()->user()->name) }}" required class="payment-field" placeholder="Como aparece en la tarjeta">
                                @error('card_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-xs uppercase tracking-widest text-neutral-500 mb-2 block" for="card_number">Número de tarjeta</label>
                                <input id="card_number" type="text" name="card_number" value="{{ old('card_number', '4532 1178 9012 3456') }}" required class="payment-field" placeholder="0000 0000 0000 0000" maxlength="19" inputmode="numeric">
                                @error('card_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs uppercase tracking-widest text-neutral-500 mb-2 block" for="card_expiry">Vencimiento</label>
                                    <input id="card_expiry" type="text" name="card_expiry" value="{{ old('card_expiry', '12/28') }}" required class="payment-field" placeholder="MM/AA" maxlength="5">
                                    @error('card_expiry') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="text-xs uppercase tracking-widest text-neutral-500 mb-2 block" for="card_cvv">CVV</label>
                                    <input id="card_cvv" type="password" name="card_cvv" value="{{ old('card_cvv', '123') }}" required class="payment-field" placeholder="***" maxlength="4" inputmode="numeric">
                                    @error('card_cvv') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                <button type="button" class="pill-btn px-6 py-3 text-sm" data-go-tab="carrito">← Volver al carrito</button>
                                <button type="submit" class="pill-btn pill-btn-primary flex-1 py-3 text-sm" id="pay-submit-btn">
                                    Simular pago — ${{ number_format($total, 0, ',', '.') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <p class="text-neutral-600">Agrega productos al carrito para continuar.</p>
            @endif
        </div>

        {{-- Pestaña: Confirmación --}}
        <div class="checkout-panel {{ $activeTab === 'confirmacion' ? 'is-active' : '' }}" data-panel="confirmacion">
            @if ($hasOrder)
                {{-- Botones fuera del área de impresión --}}
                <div class="max-w-2xl mx-auto mb-6 flex flex-col sm:flex-row gap-3 print-hidden">
                    <a href="{{ route('store.catalog') }}" class="pill-btn pill-btn-primary px-8 py-3 text-sm text-center">
                        ← Seguir comprando
                    </a>
                    <button type="button" onclick="window.print()" class="pill-btn px-8 py-3 text-sm">
                        Imprimir / Guardar PDF
                    </button>
                </div>

                {{-- Factura --}}
                <div class="max-w-2xl mx-auto scroll-card" id="invoice-print-area">
                    {{-- Encabezado --}}
                    <div class="invoice-header">
                        <div>
                            <p class="invoice-brand">POSS ATELIER</p>
                            <p class="invoice-brand-sub">Moda & Textiles · NIT 900.123.456-7</p>
                        </div>
                        <div class="text-right">
                            <p class="invoice-title">FACTURA</p>
                            <p class="invoice-ref">{{ $lastOrder['reference'] }}</p>
                        </div>
                    </div>

                    {{-- Datos generales --}}
                    <div class="invoice-meta">
                        <div>
                            <p class="invoice-meta-label">Cliente</p>
                            <p class="invoice-meta-value">{{ $lastOrder['client'] }}</p>
                        </div>
                        <div>
                            <p class="invoice-meta-label">Método de pago</p>
                            <p class="invoice-meta-value uppercase">{{ $lastOrder['method'] }}</p>
                        </div>
                        <div>
                            <p class="invoice-meta-label">Fecha</p>
                            <p class="invoice-meta-value">{{ $lastOrder['paid_at'] }}</p>
                        </div>
                    </div>

                    {{-- Tabla de productos --}}
                    <div class="invoice-table-wrap">
                        <table class="invoice-table">
                            <thead>
                                <tr>
                                    <th class="text-left">Producto</th>
                                    <th class="text-left">SKU</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-right">Precio unit.</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lastOrder['lines'] as $line)
                                    <tr>
                                        <td class="font-medium">{{ $line['nombre'] }}</td>
                                        <td class="text-neutral-500 text-xs">{{ $line['sku'] }}</td>
                                        <td class="text-center">{{ $line['cantidad'] }}</td>
                                        <td class="text-right">${{ number_format($line['precio'], 0, ',', '.') }}</td>
                                        <td class="text-right font-medium">${{ number_format($line['subtotal'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Totales --}}
                    <div class="invoice-totals">
                        <div class="invoice-totals-row">
                            <span>Base gravable (sin IVA)</span>
                            <span>${{ number_format($lastOrder['base_iva'], 0, ',', '.') }} COP</span>
                        </div>
                        <div class="invoice-totals-row">
                            <span>IVA 19%</span>
                            <span>${{ number_format($lastOrder['iva'], 0, ',', '.') }} COP</span>
                        </div>
                        <div class="invoice-totals-row invoice-totals-final">
                            <span>TOTAL</span>
                            <span>${{ number_format($lastOrder['total'], 0, ',', '.') }} COP</span>
                        </div>
                    </div>

                    {{-- Pie --}}
                    <div class="invoice-footer">
                        <div class="invoice-success-badge print-hidden">✓ Pago simulado exitoso</div>
                        <p>Esta es una factura de demostración. No tiene validez fiscal.</p>
                        <p>Gracias por tu compra en POSS ATELIER.</p>
                    </div>
                </div>
            @else
                <p class="text-neutral-600 text-center">Completa el pago para ver la confirmación.</p>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
<script>
(function () {
    /* ── Tabs ── */
    const tabs       = document.querySelectorAll('.checkout-tab[data-tab]');
    const panels     = document.querySelectorAll('.checkout-panel[data-panel]');
    const goTabBtns  = document.querySelectorAll('[data-go-tab]');
    const paymentForm = document.getElementById('payment-form');
    const payBtn     = document.getElementById('pay-submit-btn');

    function setTab(name) {
        const tabBtn = document.querySelector(`.checkout-tab[data-tab="${name}"]`);
        if (tabBtn?.disabled) return;
        tabs.forEach(t => t.classList.toggle('is-active', t.dataset.tab === name));
        panels.forEach(p => p.classList.toggle('is-active', p.dataset.panel === name));
        const url = new URL(window.location.href);
        url.searchParams.set('tab', name);
        history.replaceState({}, '', url);
    }

    tabs.forEach(tab => tab.addEventListener('click', () => setTab(tab.dataset.tab)));
    goTabBtns.forEach(btn => btn.addEventListener('click', () => setTab(btn.dataset.goTab)));

    if (paymentForm && payBtn) {
        paymentForm.addEventListener('submit', function () {
            payBtn.disabled = true;
            payBtn.textContent = 'Procesando pago...';
        });
    }

    /* ── Helpers ── */
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    function fmt(n) {
        return '$' + Math.round(n).toLocaleString('es-CO');
    }

    function showToast(msg, type) {
        const old = document.getElementById('cart-toast');
        if (old) old.remove();
        const wrap = document.createElement('div');
        wrap.id = 'cart-toast';
        wrap.style.cssText = 'position:fixed;top:6rem;left:50%;transform:translateX(-50%);z-index:9999;width:92%;max-width:28rem;transition:opacity .3s';
        const inner = document.createElement('div');
        inner.className = 'store-alert text-center';
        inner.style.color = type === 'error' ? '#991b1b' : '#166534';
        inner.textContent = msg;
        wrap.appendChild(inner);
        document.body.appendChild(wrap);
        setTimeout(() => { wrap.style.opacity = '0'; setTimeout(() => wrap.remove(), 300); }, 3000);
    }

    function updateCartBadge(count) {
        const badge = document.getElementById('cart-count-badge');
        if (!badge) return;
        if (count > 0) { badge.textContent = ' (' + count + ')'; badge.style.display = ''; }
        else { badge.style.display = 'none'; }
    }

    function updateTotals(total) {
        const el = document.getElementById('cart-total-display');
        if (el) el.textContent = fmt(total);
    }

    function showEmptyCart() {
        const panel = document.querySelector('[data-panel="carrito"]');
        if (!panel) return;
        panel.innerHTML = '<div class="text-center py-20 border border-neutral-200 rounded-2xl">' +
            '<p class="text-neutral-600 text-lg mb-6">Tu carrito está vacío.</p>' +
            '<a href="{{ route('store.catalog') }}" class="pill-btn pill-btn-primary px-8 py-3">Ver productos</a>' +
            '</div>';
        const tabPago = document.querySelector('.checkout-tab[data-tab="pago"]');
        if (tabPago) tabPago.disabled = true;
    }

    /* ── Actualizar cantidad ── */
    document.querySelectorAll('.js-update-form').forEach(function (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            const orig = btn.textContent;
            btn.disabled = true; btn.textContent = '…';

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF },
                    body: new URLSearchParams(new FormData(form)).toString(),
                });
                const data = await res.json();
                if (res.ok) {
                    const row = form.closest('tr[data-row]');
                    if (row) {
                        const cell = document.getElementById('subtotal-' + row.dataset.row);
                        if (cell) cell.textContent = fmt(data.subtotal);
                    }
                    updateTotals(data.total);
                    updateCartBadge(data.cartCount);
                    showToast('Carrito actualizado.', 'success');
                } else {
                    showToast(data.error || 'No se pudo actualizar.', 'error');
                }
            } catch (_) {
                showToast('Error de conexión.', 'error');
            } finally {
                btn.disabled = false; btn.textContent = orig;
            }
        });
    });

    /* ── Quitar producto ── */
    document.querySelectorAll('.js-remove-form').forEach(function (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true; btn.textContent = '…';

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF },
                    body: new URLSearchParams(new FormData(form)).toString(),
                });
                const data = await res.json();
                if (res.ok) {
                    const row = form.closest('tr[data-row]');
                    if (row) { row.style.opacity = '0'; row.style.transition = 'opacity .25s'; setTimeout(() => row.remove(), 250); }
                    updateTotals(data.total);
                    updateCartBadge(data.cartCount);
                    if (data.isEmpty) setTimeout(showEmptyCart, 300);
                    else showToast('Producto eliminado del carrito.', 'success');
                } else {
                    showToast(data.error || 'No se pudo eliminar.', 'error');
                    btn.disabled = false; btn.textContent = 'Quitar';
                }
            } catch (_) {
                showToast('Error de conexión.', 'error');
                btn.disabled = false; btn.textContent = 'Quitar';
            }
        });
    });
})();
</script>
@endpush

@push('styles')
<style>
/* ── Invoice layout ── */
#invoice-print-area {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 1.25rem;
    overflow: hidden;
    font-size: .875rem;
    color: #171717;
}

.invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 2rem 2rem 1.5rem;
    border-bottom: 2px solid #171717;
    background: #171717;
    color: #fff;
}
.invoice-brand     { font-size: 1.25rem; font-weight: 700; letter-spacing: .12em; }
.invoice-brand-sub { font-size: .7rem; color: #a3a3a3; margin-top: .2rem; letter-spacing: .05em; }
.invoice-title     { font-size: .65rem; letter-spacing: .2em; color: #a3a3a3; text-transform: uppercase; }
.invoice-ref       { font-size: 1.1rem; font-weight: 600; letter-spacing: .05em; margin-top: .15rem; }

.invoice-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    padding: 1.25rem 2rem;
    background: #fafafa;
    border-bottom: 1px solid #e5e5e5;
}
.invoice-meta-label { font-size: .65rem; text-transform: uppercase; letter-spacing: .1em; color: #737373; }
.invoice-meta-value { font-weight: 600; margin-top: .1rem; }

.invoice-table-wrap { padding: 0 2rem; }
.invoice-table { width: 100%; border-collapse: collapse; }
.invoice-table thead tr {
    border-bottom: 1px solid #e5e5e5;
}
.invoice-table th {
    padding: .75rem .5rem;
    font-size: .65rem;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #737373;
    font-weight: 500;
}
.invoice-table td {
    padding: .9rem .5rem;
    border-bottom: 1px solid #f5f5f5;
    font-size: .8rem;
}
.invoice-table tbody tr:last-child td { border-bottom: none; }

.invoice-totals {
    margin: 0 2rem;
    border-top: 1px solid #e5e5e5;
    padding: 1rem 0;
}
.invoice-totals-row {
    display: flex;
    justify-content: space-between;
    padding: .35rem 0;
    font-size: .8rem;
    color: #525252;
}
.invoice-totals-final {
    border-top: 2px solid #171717;
    margin-top: .5rem;
    padding-top: .75rem;
    font-weight: 700;
    font-size: 1rem;
    color: #171717;
}

.invoice-footer {
    text-align: center;
    padding: 1.5rem 2rem 2rem;
    color: #a3a3a3;
    font-size: .7rem;
    line-height: 1.8;
    border-top: 1px solid #f0f0f0;
    margin-top: 1rem;
}
.invoice-success-badge {
    display: inline-block;
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
    border-radius: 9999px;
    padding: .3rem 1rem;
    font-size: .75rem;
    font-weight: 600;
    margin-bottom: .75rem;
}

/* ── Print ── */
@page { size: A4; margin: 1cm; }

@media print {
    html, body { height: auto !important; overflow: visible !important; }
    body * { visibility: hidden; }
    #invoice-print-area,
    #invoice-print-area * { visibility: visible; }

    #invoice-print-area {
        position: absolute !important;
        top: 0 !important; left: 0 !important;
        width: 100% !important; max-width: 100% !important;
        border: none !important; border-radius: 0 !important;
        box-shadow: none !important; margin: 0 !important;
        page-break-inside: avoid;
    }

    /* Compactar secciones */
    .invoice-header   { padding: .6rem .8rem !important; }
    .invoice-brand    { font-size: .95rem !important; }
    .invoice-meta     { padding: .5rem .8rem !important; gap: .8rem !important; }
    .invoice-table-wrap { padding: 0 .8rem !important; }
    .invoice-table th,
    .invoice-table td { padding: .4rem .3rem !important; font-size: .72rem !important; }
    .invoice-totals   { margin: 0 .8rem !important; padding: .4rem 0 !important; }
    .invoice-totals-row { padding: .2rem 0 !important; font-size: .72rem !important; }
    .invoice-totals-final { font-size: .85rem !important; padding-top: .4rem !important; }
    .invoice-footer   { padding: .6rem .8rem !important; font-size: .65rem !important; }
    .invoice-success-badge { display: none !important; }

    .print-hidden { display: none !important; visibility: hidden !important; }
}
</style>
@endpush
