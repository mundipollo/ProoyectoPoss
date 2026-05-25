<style>
.pos-sidebar{background:#f7f7f8;border-right:1px solid #e5e7eb;padding:18px 14px;display:flex;flex-direction:column;justify-content:space-between}
.pos-brand{font-size:14px;font-weight:700;color:#111827}
.pos-subtitle{font-size:12px;color:#6b7280;margin-top:2px}
.pos-nav{margin-top:16px;display:flex;flex-direction:column;gap:4px}
.pos-nav-item{padding:9px 10px;border-radius:10px;font-size:13px;color:#374151;transition:.2s;text-decoration:none;display:block}
.pos-nav-item:hover{background:#eceef1}
.pos-nav-item.is-active{background:#111827;color:#fff;font-weight:600}
.pos-sidebar-footer{margin-top:auto;padding-top:12px;border-top:1px solid #e5e7eb;font-size:11px;display:grid;gap:6px;color:#9ca3af}
.pos-logout-btn{width:100%;border:0;background:0;text-align:left;padding:9px 10px;border-radius:10px;font-size:13px;color:#ef4444;cursor:pointer;transition:.2s}
.pos-logout-btn:hover{background:#fef2f2}
</style>

<aside class="pos-sidebar">
    <div style="flex:1">
        <p class="pos-brand">Facturación POSS</p>
        <p class="pos-subtitle">Operación textil diaria</p>

        <nav class="pos-nav">
            <a href="{{ route('admin.pos') }}"          class="pos-nav-item {{ request()->routeIs('admin.pos')       ? 'is-active' : '' }}">⚡ Facturar</a>
            <a href="{{ route('admin.dashboard') }}"    class="pos-nav-item {{ request()->routeIs('admin.dashboard')  ? 'is-active' : '' }}">📊 Dashboard</a>
            <a href="{{ route('products.index') }}"     class="pos-nav-item {{ request()->routeIs('products.*')       ? 'is-active' : '' }}">📦 Inventario</a>
            <a href="{{ route('admin.usuarios') }}"     class="pos-nav-item {{ request()->routeIs('admin.usuarios*')  ? 'is-active' : '' }}">👥 Usuarios</a>
            <a href="{{ route('profile.edit') }}"       class="pos-nav-item {{ request()->routeIs('profile.edit')     ? 'is-active' : '' }}">⚙️ Configuraciones</a>
        </nav>
    </div>

    <div class="pos-sidebar-footer">
        <p>Tienda en linea: habilitada</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="pos-logout-btn">↩ Cerrar sesión</button>
        </form>
    </div>
</aside>
