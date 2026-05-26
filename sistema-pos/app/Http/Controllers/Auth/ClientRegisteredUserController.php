<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ClientRegisteredUserController extends Controller
{
    /** Paso 1 – muestra el formulario de registro */
    public function create(): View
    {
        return view('auth.client-register');
    }

    /** Paso 1 – valida datos, genera OTP y envía correo */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generar OTP de 6 dígitos
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Guardar datos en sesión (contraseña ya hasheada por seguridad)
        session([
            'reg_otp' => [
                'code'       => $otp,
                'expires_at' => now()->addMinutes(10)->timestamp,
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'attempts'   => 0,
            ],
            'reg_otp_email' => $request->email,
        ]);

        // Enviar correo con el código
        try {
            Mail::to($request->email)->send(new OtpVerificationMail($otp, $request->name));
        } catch (\Exception $e) {
            // Si falla el envío, continuamos de todas formas (en dev el código se muestra en pantalla)
            logger()->warning('OTP mail failed: ' . $e->getMessage());
        }

        return redirect()->route('client.verify')
            ->with('status', 'Enviamos un código de verificación a tu correo.');
    }

    /** Paso 2 – muestra formulario de ingreso del OTP */
    public function showVerify(): View|RedirectResponse
    {
        if (! session('reg_otp')) {
            return redirect()->route('client.register')
                ->with('error', 'Primero completa el formulario de registro.');
        }

        return view('auth.client-verify-otp');
    }

    /** Paso 2 – verifica el OTP y crea la cuenta */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $otpData = session('reg_otp');

        // Sesión inexistente o expirada
        if (! $otpData) {
            return redirect()->route('client.register')
                ->with('error', 'Sesión expirada. Vuelve a registrarte.');
        }

        // Código expiró (10 minutos)
        if (now()->timestamp > $otpData['expires_at']) {
            session()->forget(['reg_otp', 'reg_otp_email']);
            return redirect()->route('client.register')
                ->with('error', 'El código expiró (10 min). Vuelve a registrarte.');
        }

        // Demasiados intentos fallidos (máx 5)
        if ($otpData['attempts'] >= 5) {
            session()->forget(['reg_otp', 'reg_otp_email']);
            return redirect()->route('client.register')
                ->with('error', 'Demasiados intentos fallidos. Regístrate de nuevo.');
        }

        // Código incorrecto
        if ($request->code !== $otpData['code']) {
            session()->put('reg_otp.attempts', $otpData['attempts'] + 1);
            $restantes = 5 - ($otpData['attempts'] + 1);
            return back()->withErrors([
                'code' => "Código incorrecto. Te quedan {$restantes} intento(s).",
            ]);
        }

        // ✅ Código correcto → crear usuario
        $user = User::create([
            'name'              => $otpData['name'],
            'email'             => $otpData['email'],
            'password'          => $otpData['password'],   // ya hasheada
            'estado'            => 'activo',
            'email_verified_at' => now(),
        ]);

        $clientRole = Role::where('nombre', 'cliente')->first();
        if ($clientRole) {
            $user->roles()->sync([$clientRole->id]);
        }

        session()->forget(['reg_otp', 'reg_otp_email']);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('store.catalog')
            ->with('status', '¡Bienvenido, ' . $user->name . '! Tu cuenta fue verificada correctamente.');
    }

    /** Reenviar un nuevo código OTP al mismo correo */
    public function resend(): RedirectResponse
    {
        $otpData = session('reg_otp');

        if (! $otpData) {
            return redirect()->route('client.register');
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        session([
            'reg_otp' => array_merge($otpData, [
                'code'       => $otp,
                'expires_at' => now()->addMinutes(10)->timestamp,
                'attempts'   => 0,
            ]),
        ]);

        try {
            Mail::to($otpData['email'])->send(new OtpVerificationMail($otp, $otpData['name']));
        } catch (\Exception $e) {
            logger()->warning('OTP resend mail failed: ' . $e->getMessage());
        }

        return back()->with('status', 'Código reenviado. Revisa tu correo.');
    }
}
