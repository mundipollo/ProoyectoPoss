<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar usuario</h2></x-slot>

    <div class="py-4">
        <div style="max-width:1700px;margin:0 auto;padding:0 12px">
            <div class="admin-shell">
                @include('admin.partials.sidebar')

                <div class="admin-content">
                    <div style="max-width:540px">

                        {{-- Cabecera --}}
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
                            <a href="{{ route('admin.usuarios') }}" style="font-size:13px;color:#6b7280;text-decoration:none">← Volver</a>
                            <p style="font-size:17px;font-weight:700;color:#111827;margin:0">Editar: {{ $user->name }}</p>
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

                        @if (session('status'))
                            <div style="margin-bottom:16px;padding:10px 14px;border-radius:10px;background:#f0fdf4;color:#166534;font-size:13px;border:1px solid #bbf7d0">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.usuarios.update', $user) }}"
                              style="background:#fff;border-radius:14px;border:1px solid #e5e7eb;padding:24px;display:flex;flex-direction:column;gap:18px">
                            @csrf @method('PUT')

                            {{-- Nombre --}}
                            <div>
                                <label class="u-label">Nombre completo</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="u-input {{ $errors->has('name') ? 'u-input-err' : '' }}">
                                @error('name') <p class="u-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="u-label">Correo electrónico</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="u-input {{ $errors->has('email') ? 'u-input-err' : '' }}">
                                @error('email') <p class="u-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Rol --}}
                            <div>
                                <label class="u-label">Rol</label>
                                @php $currentRole = $user->roles->first()?->nombre; @endphp
                                <select name="role" required class="u-input {{ $errors->has('role') ? 'u-input-err' : '' }}">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->nombre }}" {{ old('role', $currentRole) === $role->nombre ? 'selected' : '' }}>
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
                                    <option value="activo"   {{ old('estado', $user->estado ?? 'activo') === 'activo'   ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ old('estado', $user->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            {{-- Contraseña --}}
                            <div>
                                <label class="u-label">
                                    Nueva contraseña
                                    <span style="text-transform:none;font-weight:400;color:#9ca3af">(dejar vacío para no cambiar)</span>
                                </label>
                                <input type="password" name="password"
                                       class="u-input {{ $errors->has('password') ? 'u-input-err' : '' }}"
                                       placeholder="Mínimo 8 caracteres">
                                @error('password') <p class="u-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Confirmar contraseña --}}
                            <div>
                                <label class="u-label">Confirmar nueva contraseña</label>
                                <input type="password" name="password_confirmation"
                                       class="u-input" placeholder="Repite la nueva contraseña">
                            </div>

                            {{-- Botones --}}
                            <div style="display:flex;gap:10px;padding-top:4px">
                                <button type="submit" class="u-btn-primary" style="flex:1;text-align:center">
                                    ✓ Guardar cambios
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
