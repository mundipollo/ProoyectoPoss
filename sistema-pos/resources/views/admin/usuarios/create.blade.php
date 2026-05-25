<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo usuario</h2></x-slot>

    <div class="py-4">
        <div style="max-width:1700px;margin:0 auto;padding:0 12px">
            <div class="admin-shell">
                @include('admin.partials.sidebar')

                <div class="admin-content">
                    <div style="max-width:540px">

                        {{-- Cabecera --}}
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
                            <a href="{{ route('admin.usuarios') }}" style="font-size:13px;color:#6b7280;text-decoration:none">← Volver</a>
                            <p style="font-size:17px;font-weight:700;color:#111827;margin:0">Crear usuario</p>
                        </div>

                        @if ($errors->any())
                            <div style="margin-bottom:16px;padding:12px 16px;border-radius:10px;background:#fef2f2;color:#dc2626;font-size:13px;border:1px solid #fecaca">
                                <ul style="margin:0;padding-left:18px">
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.usuarios.store') }}"
                              style="background:#fff;border-radius:14px;border:1px solid #e5e7eb;padding:24px;display:flex;flex-direction:column;gap:18px">
                            @csrf

                            {{-- Nombre --}}
                            <div>
                                <label class="u-label">Nombre completo</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="u-input {{ $errors->has('name') ? 'u-input-err' : '' }}"
                                       placeholder="Ej: María García">
                                @error('name') <p class="u-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="u-label">Correo electrónico</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="u-input {{ $errors->has('email') ? 'u-input-err' : '' }}"
                                       placeholder="correo@ejemplo.com">
                                @error('email') <p class="u-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Rol --}}
                            <div>
                                <label class="u-label">Rol</label>
                                <select name="role" required class="u-input {{ $errors->has('role') ? 'u-input-err' : '' }}">
                                    <option value="">— Selecciona un rol —</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->nombre }}" {{ old('role') === $role->nombre ? 'selected' : '' }}>
                                            {{ ucfirst($role->nombre) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role') <p class="u-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Estado --}}
                            <div>
                                <label class="u-label">Estado</label>
                                <select name="estado" required class="u-input">
                                    <option value="activo"   {{ old('estado', 'activo') === 'activo'   ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('estado') <p class="u-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Contraseña --}}
                            <div>
                                <label class="u-label">Contraseña</label>
                                <input type="password" name="password" required
                                       class="u-input {{ $errors->has('password') ? 'u-input-err' : '' }}"
                                       placeholder="Mínimo 8 caracteres">
                                @error('password') <p class="u-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Confirmar contraseña --}}
                            <div>
                                <label class="u-label">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation" required
                                       class="u-input" placeholder="Repite la contraseña">
                            </div>

                            {{-- Botones --}}
                            <div style="display:flex;gap:10px;padding-top:4px">
                                <button type="submit" class="u-btn-primary" style="flex:1;text-align:center">
                                    ✓ Crear usuario
                                </button>
                                <a href="{{ route('admin.usuarios') }}"
                                   style="padding:10px 20px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;color:#6b7280;text-decoration:none;background:#fff">
                                    Cancelar
                                </a>
                            </div>
                        </form>
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

        .u-label{display:block;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px}
        .u-input{width:100%;box-sizing:border-box;border:1px solid #d1d5db;border-radius:10px;padding:10px 13px;font-size:13px;background:#fff;color:#111827;outline:none;transition:.2s}
        .u-input:focus{border-color:#111827;box-shadow:0 0 0 3px rgba(17,24,39,.08)}
        .u-input-err{border-color:#f87171}
        .u-error{color:#dc2626;font-size:11px;margin-top:4px}
    </style>
</x-app-layout>
