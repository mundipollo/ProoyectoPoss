@extends('auth.client-auth-layout')

@section('title', 'Verificar correo')

@section('content')
    <h1 class="text-xl font-medium tracking-tight text-center mb-1">Verifica tu correo</h1>
    <p class="text-sm text-neutral-500 text-center mb-6">
        Enviamos un código de 6 dígitos a<br>
        <strong style="color:#111827">{{ session('reg_otp_email') }}</strong>
    </p>

    {{-- Alerta de éxito (reenvío) --}}
    @if (session('status'))
        <div style="margin-bottom:16px;padding:10px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;font-size:12px;color:#166534;text-align:center">
            ✓ {{ session('status') }}
        </div>
    @endif

    {{-- Alerta de error --}}
    @if ($errors->has('code'))
        <div style="margin-bottom:16px;padding:10px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;font-size:12px;color:#dc2626;text-align:center">
            {{ $errors->first('code') }}
        </div>
    @endif

    {{-- 📧 Ícono correo --}}
    <div style="text-align:center;margin-bottom:24px">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:50%;background:#f3f4f6">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0l-9.75 6.75L2.25 6.75"/>
            </svg>
        </div>
    </div>

    {{-- Formulario OTP --}}
    <form method="POST" action="{{ route('client.verify.store') }}" id="otp-form">
        @csrf

        <label class="field-label" for="code">Código de verificación</label>

        {{-- Inputs de 6 dígitos separados --}}
        <div id="otp-boxes" style="display:flex;gap:8px;justify-content:center;margin:12px 0 4px">
            @for ($i = 0; $i < 6; $i++)
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                       class="otp-digit"
                       style="width:42px;height:52px;text-align:center;font-size:22px;font-weight:700;color:#111827;border:none;border-bottom:2px solid #e4e4e7;background:transparent;outline:none;transition:.2s"
                       autocomplete="off">
            @endfor
        </div>
        <input type="hidden" name="code" id="code-hidden">

        @error('code') <p class="alert" style="text-align:center">{{ $message }}</p> @enderror

        {{-- Temporizador --}}
        <p style="text-align:center;font-size:12px;color:#9ca3af;margin:12px 0 0" id="timer-msg">
            El código expira en <span id="timer-count" style="font-weight:600;color:#374151">10:00</span>
        </p>

        {{-- Dev helper: muestra el código si APP_DEBUG está activo --}}
        @if(config('app.debug'))
            @php $otpData = session('reg_otp'); @endphp
            @if($otpData)
            <div style="margin:14px 0;padding:10px 14px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;font-size:12px;color:#1e40af;text-align:center">
                🔧 <strong>Modo desarrollo</strong> — Código de prueba:
                <span style="font-family:monospace;font-size:16px;font-weight:700;letter-spacing:.2em;color:#1d4ed8">{{ $otpData['code'] }}</span>
            </div>
            @endif
        @endif

        <button type="submit" class="sign-btn" id="verify-btn">
            Verificar código
        </button>
    </form>

    {{-- Reenviar código --}}
    <div style="text-align:center;margin-top:16px">
        <p style="font-size:12px;color:#9ca3af;margin-bottom:6px">¿No recibiste el correo?</p>
        <form method="POST" action="{{ route('client.verify.resend') }}" style="display:inline">
            @csrf
            <button type="submit"
                    style="background:none;border:none;cursor:pointer;font-size:13px;color:#6b7280;text-decoration:underline;padding:0">
                Reenviar código
            </button>
        </form>
        &nbsp;·&nbsp;
        <a href="{{ route('client.register') }}" style="font-size:13px;color:#6b7280">Volver al registro</a>
    </div>

    <script>
        // ── OTP box inputs ──────────────────────────────────────────
        const boxes = document.querySelectorAll('.otp-digit');
        const hidden = document.getElementById('code-hidden');
        const form   = document.getElementById('otp-form');

        boxes.forEach((box, i) => {
            box.addEventListener('input', () => {
                box.value = box.value.replace(/\D/g, '').slice(0, 1);
                syncHidden();
                if (box.value && i < 5) boxes[i + 1].focus();
            });
            box.addEventListener('keydown', e => {
                if (e.key === 'Backspace' && !box.value && i > 0) boxes[i - 1].focus();
                if (e.key === 'ArrowLeft'  && i > 0) boxes[i - 1].focus();
                if (e.key === 'ArrowRight' && i < 5) boxes[i + 1].focus();
            });
            box.addEventListener('paste', e => {
                e.preventDefault();
                const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                text.split('').forEach((ch, j) => { if (boxes[j]) boxes[j].value = ch; });
                syncHidden();
                const last = Math.min(text.length, 5);
                boxes[last].focus();
            });
            box.addEventListener('focus', () => {
                box.style.borderBottomColor = '#111827';
            });
            box.addEventListener('blur', () => {
                box.style.borderBottomColor = '#e4e4e7';
            });
        });

        function syncHidden() {
            hidden.value = Array.from(boxes).map(b => b.value).join('');
        }

        // Validar antes de enviar
        form.addEventListener('submit', e => {
            syncHidden();
            if (hidden.value.length < 6) {
                e.preventDefault();
                alert('Por favor ingresa los 6 dígitos del código.');
                boxes[0].focus();
            }
        });

        // ── Temporizador 10 min ──────────────────────────────────────
        let totalSeconds = 600;
        const timerEl = document.getElementById('timer-count');
        const timerMsg = document.getElementById('timer-msg');
        const verifyBtn = document.getElementById('verify-btn');

        const interval = setInterval(() => {
            totalSeconds--;
            if (totalSeconds <= 0) {
                clearInterval(interval);
                timerMsg.innerHTML = '⚠️ El código expiró. <a href="{{ route('client.register') }}" style="color:#dc2626">Regístrate de nuevo</a>.';
                verifyBtn.disabled = true;
                verifyBtn.style.opacity = '.5';
                verifyBtn.style.cursor = 'not-allowed';
                return;
            }
            const m = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
            const s = String(totalSeconds % 60).padStart(2, '0');
            timerEl.textContent = `${m}:${s}`;
            if (totalSeconds <= 60) timerEl.style.color = '#dc2626';
        }, 1000);

        // Auto-focus primer box
        boxes[0].focus();
    </script>
@endsection
