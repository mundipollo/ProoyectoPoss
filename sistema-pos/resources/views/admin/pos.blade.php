<x-app-layout>
    <x-slot name="header">
        <h2 style="font-weight:700;font-size:20px;color:#111827">POS Moda — Facturación</h2>
    </x-slot>

    <div class="py-4">
        <div style="max-width:1700px;margin:0 auto;padding:0 12px">
            <div class="pos-shell">
                @include('admin.partials.sidebar')

                {{-- ── Catálogo ─────────────────────────────────────────── --}}
                <section class="pos-center">
                    <div class="pos-panel-head">
                        <h3>Catálogo de productos</h3>
                        <span class="pos-badge">Textil de moda</span>
                    </div>

                    <div class="pos-search-grid">
                        <div>
                            <label class="pos-label">Producto</label>
                            {{-- Sin list= ni datalist: el JS busca directamente en el array --}}
                            <input id="product-search" type="text"
                                   placeholder="Buscar por nombre o SKU…" class="pos-input">
                        </div>
                        <div>
                            <label class="pos-label">Cantidad</label>
                            <input id="product-qty" type="number" min="1" value="1" class="pos-input">
                        </div>
                        <div class="pos-add-wrap">
                            <button id="add-product" type="button" class="pos-btn-main">Agregar</button>
                        </div>
                    </div>

                    <div class="pos-card-grid">
                        @forelse (array_slice($products, 0, 12) as $product)
                            <button type="button" class="pos-product-card"
                                data-product-card="1"
                                data-id="{{ $product['id'] }}"
                                data-name="{{ $product['nombre'] }}"
                                data-sku="{{ $product['sku'] }}"
                                data-price="{{ $product['precio'] }}">
                                <p class="pos-product-name">{{ $product['nombre'] }}</p>
                                <p class="pos-product-sku">{{ $product['sku'] }}</p>
                                <div class="pos-product-meta">
                                    <span>$ {{ number_format($product['precio'], 0, ',', '.') }} COP</span>
                                    <span>Stock {{ $product['stock_actual'] }}</span>
                                </div>
                            </button>
                        @empty
                            <div class="pos-empty-card">No hay productos activos para mostrar.</div>
                        @endforelse
                    </div>
                </section>

                {{-- ── Factura ──────────────────────────────────────────── --}}
                <section class="pos-right">
                    <div class="pos-panel-head right">
                        <h3>Factura de venta</h3>
                        <span style="font-size:12px;color:#6b7280">Caja principal</span>
                    </div>

                    <div class="pos-form-grid">
                        <div>
                            <label class="pos-label">Lista de precio</label>
                            <select class="pos-input">
                                <option>General</option>
                                <option>Mayorista</option>
                                <option>Temporada</option>
                            </select>
                        </div>
                        <div>
                            <label class="pos-label">Numeración</label>
                            <select class="pos-input">
                                <option>Principal</option>
                                <option>Punto físico</option>
                                <option>E-commerce</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-top:12px">
                        <label class="pos-label">Cliente</label>
                        <input id="customer-search" list="customers-list" type="text"
                               placeholder="Buscar cliente por nombre o documento…" class="pos-input">
                        <datalist id="customers-list">
                            @foreach ($customers as $c)
                                <option value="{{ $c['nombre'] }}{{ $c['numero_documento'] ? ' ('.$c['numero_documento'].')' : '' }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="pos-cart">
                        <table>
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cant.</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cart-body">
                                <tr id="cart-empty">
                                    <td colspan="4" class="pos-cart-empty">Aquí verás los productos para la venta.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pos-totals">
                        <div><span>Items</span><span id="cart-count">0</span></div>
                        <div><span>Total</span><span id="cart-total">$ 0 COP</span></div>
                    </div>

                    <button type="button" class="pos-btn-sell" id="btn-vender" style="width:100%">Vender</button>
                </section>
            </div>
        </div>
    </div>

    {{-- ── Modal de pago ────────────────────────────────────────────────── --}}
    <div id="pay-modal" class="pay-modal-overlay" style="display:none">
        <div class="pay-modal-box">

            <div id="pay-step-1">
                <div class="pay-modal-head">
                    <h3>Procesar pago</h3>
                    <button type="button" id="pay-modal-close" class="pay-close-btn">✕</button>
                </div>

                <div id="pay-order-summary" class="pay-summary"></div>
                <div class="pay-divider"></div>

                <div style="margin-bottom:12px">
                    <label class="pos-label">Cliente (opcional)</label>
                    <input type="text" id="pay-cliente" class="pos-input" placeholder="Nombre del cliente o mostrador">
                </div>

                <div style="margin-bottom:12px">
                    <label class="pos-label">Método de pago</label>
                    <div class="pay-methods">
                        <label class="pay-method-opt"><input type="radio" name="pay_method" value="tarjeta" checked> Tarjeta</label>
                        <label class="pay-method-opt"><input type="radio" name="pay_method" value="pse"> PSE</label>
                        <label class="pay-method-opt"><input type="radio" name="pay_method" value="nequi"> Nequi</label>
                    </div>
                </div>

                <div class="pay-card-fields">
                    <div style="margin-bottom:10px">
                        <label class="pos-label">Nombre en la tarjeta</label>
                        <input type="text" id="pay-card-name" class="pos-input" value="Cliente Mostrador">
                    </div>
                    <div style="margin-bottom:10px">
                        <label class="pos-label">Número de tarjeta</label>
                        <input type="text" id="pay-card-number" class="pos-input" value="4532 1178 9012 3456" maxlength="19">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px">
                        <div>
                            <label class="pos-label">Vencimiento</label>
                            <input type="text" id="pay-card-expiry" class="pos-input" value="12/28" maxlength="5">
                        </div>
                        <div>
                            <label class="pos-label">CVV</label>
                            <input type="password" id="pay-card-cvv" class="pos-input" value="123" maxlength="4">
                        </div>
                    </div>
                </div>

                <div id="pay-error" class="pay-error" style="display:none"></div>

                <button type="button" id="pay-submit-btn" class="pos-btn-sell" style="width:100%;margin-top:8px">
                    Confirmar pago
                </button>
                <button type="button" id="pay-cancel-btn" class="pay-cancel-link">Cancelar</button>
            </div>

            <div id="pay-step-2" style="display:none">
                <div id="pos-receipt"></div>
                <div style="display:flex;gap:10px;margin-top:16px">
                    <button type="button" onclick="window.print()" class="pos-btn-main" style="flex:1">🖨 Imprimir recibo</button>
                    <button type="button" id="pay-nueva-venta" class="pos-btn-sell" style="flex:1">Nueva venta</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ── Layout ───────────────────────────────────────────────────────── */
        .pos-shell{display:grid;grid-template-columns:230px 1fr 430px;min-height:78vh;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;background:#fff}
        .pos-center{padding:16px;border-right:1px solid #e5e7eb;background:#fcfcfd}
        .pos-right{padding:16px;background:#fff;display:flex;flex-direction:column}

        /* ── Componentes compartidos ──────────────────────────────────────── */
        .pos-panel-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
        .pos-panel-head h3{font-size:18px;color:#111827;font-weight:700}
        .pos-badge{background:#111827;color:#fff;font-size:11px;border-radius:9999px;padding:3px 10px}
        .pos-label{display:block;margin-bottom:4px;font-size:12px;color:#6b7280}
        .pos-input{width:100%;border:1px solid #d1d5db;border-radius:10px;font-size:13px;padding:9px 10px;background:#fff;box-sizing:border-box}
        .pos-input:focus{outline:none;border-color:#111827;box-shadow:0 0 0 2px rgba(17,24,39,.1)}

        /* ── Catálogo ─────────────────────────────────────────────────────── */
        .pos-search-grid{display:grid;grid-template-columns:1.6fr .6fr .5fr;gap:10px;margin-bottom:14px}
        .pos-add-wrap{display:flex;align-items:flex-end}
        .pos-btn-main{width:100%;border:0;border-radius:10px;background:#111827;color:#fff;padding:10px 12px;font-size:13px;font-weight:600;cursor:pointer}
        .pos-btn-main:hover{background:#1f2937}
        .pos-card-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}
        .pos-product-card{border:1px solid #e5e7eb;border-radius:12px;background:#fff;padding:11px;text-align:left;cursor:pointer;transition:.2s;width:100%}
        .pos-product-card:hover{transform:translateY(-1px);border-color:#111827;box-shadow:0 8px 18px rgba(0,0,0,.06)}
        .pos-product-name{color:#111827;font-size:13px;font-weight:600;line-height:1.2;min-height:34px}
        .pos-product-sku{font-size:11px;color:#6b7280;margin-top:2px}
        .pos-product-meta{margin-top:8px;font-size:11px;color:#374151;display:flex;justify-content:space-between}
        .pos-empty-card{grid-column:1/-1;border:1px dashed #d1d5db;border-radius:10px;padding:16px;text-align:center;color:#6b7280;font-size:13px}

        /* ── Factura ──────────────────────────────────────────────────────── */
        .pos-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
        .pos-cart{margin-top:12px;border:1px solid #e5e7eb;border-radius:12px;overflow:auto;max-height:380px}
        .pos-cart table{width:100%;border-collapse:collapse;font-size:13px}
        .pos-cart thead th{text-align:left;font-size:11px;color:#6b7280;padding:8px 10px;background:#f9fafb;border-bottom:1px solid #e5e7eb}
        .pos-cart tbody td{padding:9px 10px;border-bottom:1px solid #f3f4f6;color:#111827;vertical-align:top}
        .pos-cart-empty{text-align:center;color:#6b7280;padding:24px 10px!important}
        .pos-totals{margin-top:12px;display:grid;gap:6px;font-size:13px;color:#374151}
        .pos-totals>div{display:flex;justify-content:space-between}
        .pos-btn-sell{margin-top:12px;border:0;border-radius:10px;background:#111827;color:#fff;padding:11px 12px;font-size:14px;font-weight:700;cursor:pointer}
        .pos-btn-sell:hover{background:#1f2937}

        /* ── Modal ────────────────────────────────────────────────────────── */
        .pay-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:1000;display:flex;align-items:center;justify-content:center;padding:16px}
        .pay-modal-box{background:#fff;border-radius:18px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;padding:24px;box-shadow:0 20px 60px rgba(0,0,0,.2)}
        .pay-modal-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
        .pay-modal-head h3{font-size:18px;font-weight:700;color:#111827}
        .pay-close-btn{border:0;background:0;font-size:18px;color:#9ca3af;cursor:pointer;padding:2px 6px}
        .pay-close-btn:hover{color:#111827}
        .pay-summary{background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:12px;font-size:13px}
        .pay-summary-row{display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid #f3f4f6}
        .pay-summary-total{display:flex;justify-content:space-between;padding:8px 0 0;font-weight:700;font-size:15px}
        .pay-divider{border-top:1px solid #e5e7eb;margin:16px 0}
        .pay-methods{display:flex;gap:8px;flex-wrap:wrap}
        .pay-method-opt{display:flex;align-items:center;gap:6px;border:1px solid #d1d5db;border-radius:10px;padding:8px 14px;font-size:13px;cursor:pointer;transition:.2s}
        .pay-method-opt:has(input:checked){border-color:#111827;background:#111827;color:#fff}
        .pay-error{background:#fef2f2;border:1px solid #fecaca;color:#dc2626;border-radius:10px;padding:10px 14px;font-size:13px;margin-top:8px}
        .pay-cancel-link{display:block;width:100%;text-align:center;margin-top:10px;font-size:13px;color:#9ca3af;background:0;border:0;cursor:pointer}
        .pay-cancel-link:hover{color:#111827}

        /* ── Recibo ───────────────────────────────────────────────────────── */
        .pos-receipt-wrap{border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;font-size:13px}
        .pos-receipt-head{background:#111827;color:#fff;padding:14px 18px;display:flex;justify-content:space-between;align-items:flex-start}
        .pos-receipt-brand{font-size:15px;font-weight:700;letter-spacing:.08em}
        .pos-receipt-meta{display:flex;gap:20px;padding:10px 18px;background:#f9fafb;border-bottom:1px solid #e5e7eb;font-size:12px}
        .pos-receipt-meta-item span:first-child{display:block;color:#9ca3af;font-size:10px;text-transform:uppercase;letter-spacing:.06em}
        .pos-receipt-table{width:100%;border-collapse:collapse}
        .pos-receipt-table th{font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;padding:8px 18px;border-bottom:1px solid #f0f0f0;text-align:left}
        .pos-receipt-table td{padding:8px 18px;border-bottom:1px solid #f9f9f9;font-size:12px}
        .pos-receipt-totals{padding:10px 18px;border-top:1px solid #e5e7eb}
        .pos-receipt-totals-row{display:flex;justify-content:space-between;font-size:12px;color:#6b7280;padding:2px 0}
        .pos-receipt-totals-final{display:flex;justify-content:space-between;font-weight:700;font-size:15px;border-top:2px solid #111827;margin-top:6px;padding-top:8px}
        .pos-receipt-footer{text-align:center;padding:12px 18px;font-size:11px;color:#9ca3af}

        /* ── Responsive ───────────────────────────────────────────────────── */
        @media(max-width:1280px){.pos-shell{grid-template-columns:200px 1fr}.pos-right{grid-column:1/-1;border-top:1px solid #e5e7eb}.pos-center{border-right:0}}
        @media(max-width:900px){.pos-shell{grid-template-columns:1fr}.pos-search-grid{grid-template-columns:1fr}.pos-card-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media print{body *{visibility:hidden}#pos-receipt,#pos-receipt *{visibility:visible}#pos-receipt{position:fixed;inset:0;overflow:auto}.pay-modal-overlay{display:none!important}}
    </style>

    <script>
    (() => {
        const products   = @json($productsJs);
        const CSRF       = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        const VENDER_URL = '{{ route("admin.pos.vender") }}';

        const productInput = document.getElementById('product-search');
        const qtyInput     = document.getElementById('product-qty');
        const cartBody     = document.getElementById('cart-body');
        const cartEmpty    = document.getElementById('cart-empty');
        const cartCount    = document.getElementById('cart-count');
        const cartTotal    = document.getElementById('cart-total');
        const cart         = [];

        const fmt = v => `$ ${Math.round(Number(v)).toLocaleString('es-CO')} COP`;
        const matchProduct = q => {
            const lq = q.toLowerCase();
            return products.find(p => p.sku.toLowerCase().includes(lq) || p.nombre.toLowerCase().includes(lq));
        };

        /* ── Autocompletado liviano ──────────────────────────────────────── */
        const suggestions = document.createElement('div');
        suggestions.style.cssText = 'position:absolute;background:#fff;border:1px solid #e5e7eb;border-radius:10px;z-index:200;width:100%;max-height:180px;overflow-y:auto;display:none;box-shadow:0 8px 24px rgba(0,0,0,.1)';
        productInput.parentElement.style.position = 'relative';
        productInput.parentElement.appendChild(suggestions);

        productInput.addEventListener('input', () => {
            const q = productInput.value.trim();
            if (q.length < 2) { suggestions.style.display = 'none'; return; }
            const hits = products.filter(p => p.sku.toLowerCase().includes(q.toLowerCase()) || p.nombre.toLowerCase().includes(q.toLowerCase())).slice(0, 8);
            if (!hits.length) { suggestions.style.display = 'none'; return; }
            suggestions.innerHTML = hits.map(p =>
                `<div data-sid="${p.id}" style="padding:8px 12px;font-size:13px;cursor:pointer;border-bottom:1px solid #f3f4f6">
                    <span style="font-weight:600">${p.nombre}</span>
                    <span style="color:#9ca3af;font-size:11px;margin-left:6px">${p.sku}</span>
                </div>`
            ).join('');
            suggestions.style.display = 'block';
            suggestions.querySelectorAll('[data-sid]').forEach(el => {
                el.addEventListener('mouseenter', () => el.style.background = '#f9fafb');
                el.addEventListener('mouseleave', () => el.style.background = '');
                el.addEventListener('click', () => {
                    const p = products.find(x => x.id === Number(el.dataset.sid));
                    if (p) addToCart(p);
                    suggestions.style.display = 'none';
                });
            });
        });
        document.addEventListener('click', e => { if (!productInput.contains(e.target)) suggestions.style.display = 'none'; });

        /* ── Carrito ─────────────────────────────────────────────────────── */
        const render = () => {
            cartBody.querySelectorAll('tr[data-row]').forEach(r => r.remove());
            if (!cart.length) {
                cartEmpty.hidden = false;
                cartCount.textContent = '0';
                cartTotal.textContent = fmt(0);
                return;
            }
            cartEmpty.hidden = true;
            let total = 0;
            cart.forEach((item, i) => {
                total += item.subtotal;
                const tr = document.createElement('tr');
                tr.dataset.row = '1';
                tr.innerHTML = `<td><p style="font-weight:600">${item.nombre}</p><p style="font-size:11px;color:#6b7280">${item.sku}</p></td>
                    <td>${item.cantidad}</td><td>${fmt(item.subtotal)}</td>
                    <td style="text-align:right"><button type="button" data-remove="${i}" style="font-size:11px;color:#dc2626;border:0;background:0;cursor:pointer">Quitar</button></td>`;
                cartBody.appendChild(tr);
            });
            cartCount.textContent = String(cart.length);
            cartTotal.textContent = fmt(total);
        };

        const addToCart = p => {
            const qty = Math.max(1, Number(qtyInput.value || 1));
            cart.push({ id: p.id, nombre: p.nombre, sku: p.sku, precio: Number(p.precio), cantidad: qty, subtotal: qty * Number(p.precio) });
            productInput.value = '';
            qtyInput.value = '1';
            render();
        };

        document.getElementById('add-product')?.addEventListener('click', () => {
            const p = matchProduct(productInput.value.trim());
            if (p) addToCart(p);
        });
        document.querySelectorAll('[data-product-card]').forEach(card =>
            card.addEventListener('click', () => addToCart({
                id: Number(card.dataset.id), nombre: card.dataset.name,
                sku: card.dataset.sku, precio: Number(card.dataset.price)
            }))
        );
        cartBody?.addEventListener('click', e => {
            const btn = e.target.closest('[data-remove]');
            if (btn) { cart.splice(Number(btn.dataset.remove), 1); render(); }
        });

        /* ── Modal de pago ───────────────────────────────────────────────── */
        const modal     = document.getElementById('pay-modal');
        const step1     = document.getElementById('pay-step-1');
        const step2     = document.getElementById('pay-step-2');
        const errBox    = document.getElementById('pay-error');
        const summary   = document.getElementById('pay-order-summary');
        const submitBtn = document.getElementById('pay-submit-btn');

        const openModal = () => {
            if (!cart.length) { alert('Agrega productos al carrito primero.'); return; }
            step1.style.display = 'block'; step2.style.display = 'none'; errBox.style.display = 'none';
            const total = cart.reduce((s, i) => s + i.subtotal, 0);
            summary.innerHTML = cart.map(i => `<div class="pay-summary-row"><span>${i.nombre} × ${i.cantidad}</span><span>${fmt(i.subtotal)}</span></div>`).join('')
                + `<div class="pay-summary-total"><span>Total</span><span>${fmt(total)}</span></div>`;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        };
        const closeModal = () => { modal.style.display = 'none'; document.body.style.overflow = ''; };

        document.getElementById('btn-vender')?.addEventListener('click', openModal);
        document.getElementById('pay-modal-close')?.addEventListener('click', closeModal);
        document.getElementById('pay-cancel-btn')?.addEventListener('click', closeModal);
        modal?.addEventListener('click', e => { if (e.target === modal) closeModal(); });
        document.getElementById('pay-nueva-venta')?.addEventListener('click', () => { cart.length = 0; render(); closeModal(); });

        submitBtn?.addEventListener('click', async () => {
            const method  = document.querySelector('input[name="pay_method"]:checked')?.value || 'tarjeta';
            const cliente = document.getElementById('pay-cliente')?.value.trim() || '';
            submitBtn.disabled = true; submitBtn.textContent = 'Procesando…'; errBox.style.display = 'none';
            try {
                const res  = await fetch(VENDER_URL, { method:'POST',
                    headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},
                    body: JSON.stringify({ items: cart, payment_method: method, cliente }) });
                const data = await res.json();
                if (!res.ok) throw new Error(data.error || 'Error al procesar el pago.');
                buildReceipt(data);
                step1.style.display = 'none'; step2.style.display = 'block';
            } catch (err) {
                errBox.textContent = err.message; errBox.style.display = 'block';
            } finally {
                submitBtn.disabled = false; submitBtn.textContent = 'Confirmar pago';
            }
        });

        /* ── Recibo ──────────────────────────────────────────────────────── */
        const buildReceipt = d => {
            const fmtN = n => `$${Math.round(n).toLocaleString('es-CO')}`;
            document.getElementById('pos-receipt').innerHTML = `
            <div class="pos-receipt-wrap">
                <div class="pos-receipt-head">
                    <div><p class="pos-receipt-brand">POSS ATELIER</p>
                         <p style="font-size:11px;color:#9ca3af;margin-top:2px">Moda & Textiles · NIT 900.123.456-7</p></div>
                    <div style="text-align:right">
                        <p style="font-size:10px;color:#9ca3af;letter-spacing:.15em">FACTURA POS</p>
                        <p style="font-weight:700;margin-top:2px">${d.reference}</p></div>
                </div>
                <div class="pos-receipt-meta">
                    <div class="pos-receipt-meta-item"><span>Cliente</span><span style="font-weight:600">${d.cliente}</span></div>
                    <div class="pos-receipt-meta-item"><span>Método</span><span style="font-weight:600;text-transform:uppercase">${d.method}</span></div>
                    <div class="pos-receipt-meta-item"><span>Fecha</span><span style="font-weight:600">${d.paid_at}</span></div>
                </div>
                <table class="pos-receipt-table">
                    <thead><tr><th>Producto</th><th>SKU</th><th style="text-align:center">Cant.</th><th style="text-align:right">Precio</th><th style="text-align:right">Subtotal</th></tr></thead>
                    <tbody>${d.items.map(i=>`<tr>
                        <td style="font-weight:600">${i.nombre}</td>
                        <td style="color:#6b7280;font-size:11px">${i.sku||''}</td>
                        <td style="text-align:center">${i.cantidad}</td>
                        <td style="text-align:right">${fmtN(i.precio)}</td>
                        <td style="text-align:right;font-weight:600">${fmtN(i.subtotal)}</td>
                    </tr>`).join('')}</tbody>
                </table>
                <div class="pos-receipt-totals">
                    <div class="pos-receipt-totals-row"><span>Base gravable (sin IVA)</span><span>${fmtN(d.base_iva)} COP</span></div>
                    <div class="pos-receipt-totals-row"><span>IVA 19 %</span><span>${fmtN(d.iva)} COP</span></div>
                    <div class="pos-receipt-totals-final"><span>TOTAL</span><span>${fmtN(d.total)} COP</span></div>
                </div>
                <div class="pos-receipt-footer">
                    <p style="color:#166534;font-weight:600;margin-bottom:4px">✓ Pago simulado exitoso</p>
                    <p>Gracias por tu compra en POSS ATELIER.</p>
                    <p>Esta es una factura de demostración. No tiene validez fiscal.</p>
                </div>
            </div>`;
        };
    })();
    </script>
</x-app-layout>
