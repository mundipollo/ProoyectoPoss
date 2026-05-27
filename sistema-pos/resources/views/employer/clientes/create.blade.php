<x-app-layout>
    <x-slot name="header">
        <h2 style="font-weight:700;font-size:20px;color:#111827">👤 Registrar cliente</h2>
    </x-slot>

    <div class="py-4">
        <div style="max-width:1400px;margin:0 auto;padding:0 20px">
            <div class="emp-shell">
                @include('employer.partials.sidebar')

                <main class="emp-main">

                    @if(session('status'))
                        <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;border-radius:12px;padding:14px 18px;margin-bottom:20px;font-size:14px;font-weight:600">
                            ✅ {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;border-radius:12px;padding:14px 18px;margin-bottom:20px;font-size:13px">
                            <p style="font-weight:700;margin:0 0 6px">Por favor corrige los siguientes errores:</p>
                            <ul style="margin:0;padding-left:18px">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:28px;max-width:560px">
                        <div style="margin-bottom:22px">
                            <h3 style="font-size:17px;font-weight:700;color:#111827;margin:0 0 4px">Nuevo cliente</h3>
                            <p style="font-size:13px;color:#6b7280;margin:0">Los datos registrados aquí crean una cuenta con acceso a la tienda en línea.</p>
                        </div>

                        <form method="POST" action="{{ route('employer.clientes.store') }}">
                            @csrf

                            {{-- Nombre --}}
                            <div style="margin-bottom:16px">
                                <label style="display:block;font-size:12px;color:#6b7280;margin-bottom:5px;font-weight:600;text-transform:uppercase;letter-spacing:.05em">
                                    Nombre completo <span style="color:#dc2626">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autocomplete="name"
                                    placeholder="Ej. María García"
                                    style="width:100%;border:1px solid {{ $errors->has('name') ? '#fca5a5' : '#d1d5db' }};border-radius:10px;font-size:14px;padding:10px 12px;background:#fff;box-sizing:border-box;outline:none"
                                    onfocus="this.style.borderColor='#111827'"
                                    onblur="this.style.borderColor='{{ $errors->has('name') ? '#fca5a5' : '#d1d5db' }}'"
                                >
                                @error('name')<p style="font-size:12px;color:#dc2626;margin:4px 0 0">{{ $message }}</p>@enderror
                            </div>

                            {{-- Correo --}}
                            <div style="margin-bottom:16px">
                                <label style="display:block;font-size:12px;color:#6b7280;margin-bottom:5px;font-weight:600;text-transform:uppercase;letter-spacing:.05em">
                                    Correo electrónico <span style="color:#dc2626">*</span>
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    placeholder="cliente@correo.com"
                                    style="width:100%;border:1px solid {{ $errors->has('email') ? '#fca5a5' : '#d1d5db' }};border-radius:10px;font-size:14px;padding:10px 12px;background:#fff;box-sizing:border-box;outline:none"
                                    onfocus="this.style.borderColor='#111827'"
                                    onblur="this.style.borderColor='{{ $errors->has('email') ? '#fca5a5' : '#d1d5db' }}'"
                                >
                                @error('email')<p style="font-size:12px;color:#dc2626;margin:4px 0 0">{{ $message }}</p>@enderror
                            </div>

                            {{-- Contraseña --}}
                            <div style="margin-bottom:16px">
                                <label style="display:block;font-size:12px;color:#6b7280;margin-bottom:5px;font-weight:600;text-transform:uppercase;letter-spacing:.05em">
                                    Contraseña <span style="color:#dc2626">*</span>
                                </label>
                                <input
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Mínimo 8 caracteres"
                                    style="width:100%;border:1px solid {{ $errors->has('password') ? '#fca5a5' : '#d1d5db' }};border-radius:10px;font-size:14px;padding:10px 12px;background:#fff;box-sizing:border-box;outline:none"
                                    onfocus="this.style.borderColor='#111827'"
                                    onblur="this.style.borderColor='{{ $errors->has('password') ? '#fca5a5' : '#d1d5db' }}'"
                                >
                                @error('password')<p style="font-size:12px;color:#dc2626;margin:4px 0 0">{{ $message }}</p>@enderror
                            </div>

                            {{-- Confirmar contraseña --}}
                            <div style="margin-bottom:24px">
                                <label style="display:block;font-size:12px;color:#6b7280;margin-bottom:5px;font-weight:600;text-transform:uppercase;letter-spacing:.05em">
                                    Confirmar contraseña <span style="color:#dc2626">*</span>
                                </label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Repite la contraseña"
                                    style="width:100%;border:1px solid #d1d5db;border-radius:10px;font-size:14px;padding:10px 12px;background:#fff;box-sizing:border-box;outline:none"
                                    onfocus="this.style.borderColor='#111827'"
                                    onblur="this.style.borderColor='#d1d5db'"
                                >
                            </div>

                            {{-- Rol (fijo, solo informativo) --}}
                            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:12px 14px;margin-bottom:24px;display:flex;align-items:center;gap:10px">
                                <span style="font-size:18px">👤</span>
                                <div>
                                    <p style="font-size:12px;color:#9ca3af;margin:0">Rol asignado automáticamente</p>
                                    <p style="font-size:14px;font-weight:700;color:#111827;margin:2px 0 0">Cliente</p>
                                </div>
                            </div>

                            <button
                                type="submit"
                                style="width:100%;border:0;border-radius:10px;background:#111827;color:#fff;padding:12px;font-size:15px;font-weight:700;cursor:pointer;transition:.2s"
                                onmouseover="this.style.background='#1f2937'"
                                onmouseout="this.style.background='#111827'"
                            >
                                Registrar cliente
                            </button>
                        </form>
                    </div>

                </main>
            </div>
        </div>
    </div>

    <style>
        .emp-shell{display:grid;grid-template-columns:230px 1fr;min-height:78vh;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;background:#fff}
        .emp-main{padding:24px;background:#fcfcfd;overflow-y:auto}
        @media(max-width:800px){.emp-shell{grid-template-columns:1fr}}
    </style>
</x-app-layout>
