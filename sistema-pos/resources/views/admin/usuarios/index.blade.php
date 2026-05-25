<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Usuarios</h2></x-slot>

    <div class="py-4">
        <div style="max-width:1700px;margin:0 auto;padding:0 12px">
            <div class="admin-shell">
                @include('admin.partials.sidebar')

                <div class="admin-content">

                    {{-- Cabecera con botón --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                        <div>
                            <p style="font-size:18px;font-weight:700;color:#111827">Gestión de usuarios</p>
                            <p style="font-size:12px;color:#6b7280;margin-top:2px">Administra empleados y clientes del sistema</p>
                        </div>
                        <a href="{{ route('admin.usuarios.create') }}" class="u-btn-primary">
                            + Nuevo usuario
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="u-alert-ok">{{ session('status') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="u-alert-err">{{ session('error') }}</div>
                    @endif

                    {{-- Tabla --}}
                    <div style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden">
                        <table style="width:100%;border-collapse:collapse;font-size:13px">
                            <thead>
                                <tr style="border-bottom:1px solid #f3f4f6;background:#fafafa">
                                    <th class="u-th">Nombre</th>
                                    <th class="u-th">Email</th>
                                    <th class="u-th">Rol</th>
                                    <th class="u-th">Estado</th>
                                    <th class="u-th">Creado</th>
                                    <th class="u-th">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    @php $activo = ($user->estado ?? 'activo') === 'activo'; @endphp
                                    <tr class="u-tr">
                                        <td class="u-td" style="font-weight:600">{{ $user->name }}</td>
                                        <td class="u-td" style="color:#6b7280">{{ $user->email }}</td>
                                        <td class="u-td">
                                            @foreach ($user->roles as $role)
                                                <span class="u-badge {{ $role->nombre === 'admin' ? 'u-badge-purple' : ($role->nombre === 'cliente' ? 'u-badge-blue' : 'u-badge-amber') }}">
                                                    {{ ucfirst($role->nombre) }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="u-td">
                                            <span class="u-badge {{ $activo ? 'u-badge-green' : 'u-badge-gray' }}">
                                                {{ $activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="u-td" style="color:#9ca3af;font-size:12px">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="u-td">
                                            <div style="display:flex;gap:12px;align-items:center">
                                                <a href="{{ route('admin.usuarios.edit', $user) }}" class="u-action-link" style="color:#4f46e5">Editar</a>

                                                <form action="{{ route('admin.usuarios.toggle', $user) }}" method="POST" style="display:inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="u-action-link" style="color:{{ $activo ? '#d97706' : '#16a34a' }}">
                                                        {{ $activo ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.usuarios.destroy', $user) }}" method="POST" style="display:inline"
                                                      onsubmit="return confirm('¿Eliminar a {{ addslashes($user->name) }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="u-action-link" style="color:#ef4444">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="padding:48px;text-align:center;color:#9ca3af">
                                            No hay usuarios registrados.
                                            <br><br>
                                            <a href="{{ route('admin.usuarios.create') }}" class="u-btn-primary" style="display:inline-block">
                                                + Crear primer usuario
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div style="padding:12px 16px;border-top:1px solid #f3f4f6">
                            {{ $users->links() }}
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

        .u-btn-primary{display:inline-block;padding:10px 20px;background:#111827;color:#fff;font-size:13px;font-weight:600;border-radius:10px;text-decoration:none;border:0;cursor:pointer;transition:.2s}
        .u-btn-primary:hover{background:#374151}

        .u-th{padding:10px 16px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;font-weight:500}
        .u-td{padding:13px 16px;border-bottom:1px solid #f9fafb;vertical-align:middle}
        .u-tr:hover td{background:#fafafa}
        .u-tr:last-child td{border-bottom:0}

        .u-badge{display:inline-block;padding:3px 10px;border-radius:9999px;font-size:11px;font-weight:600}
        .u-badge-purple{background:#f3e8ff;color:#7c3aed}
        .u-badge-blue{background:#dbeafe;color:#1d4ed8}
        .u-badge-amber{background:#fef3c7;color:#b45309}
        .u-badge-green{background:#dcfce7;color:#15803d}
        .u-badge-gray{background:#f3f4f6;color:#6b7280}

        .u-action-link{background:0;border:0;cursor:pointer;font-size:12px;font-weight:500;text-decoration:none;padding:0}
        .u-action-link:hover{text-decoration:underline}

        .u-alert-ok{margin-bottom:14px;padding:10px 14px;border-radius:10px;background:#f0fdf4;color:#166534;font-size:13px;border:1px solid #bbf7d0}
        .u-alert-err{margin-bottom:14px;padding:10px 14px;border-radius:10px;background:#fef2f2;color:#dc2626;font-size:13px;border:1px solid #fecaca}
    </style>
</x-app-layout>
