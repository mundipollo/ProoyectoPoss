<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ClientAuthController extends Controller
{
    public function create(): View
    {
        return view('auth.client-login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Rate limiting
        $key = Str::transliterate(Str::lower($request->string('email')).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)]),
            ]);
        }

        // Intentar autenticar con cualquier cuenta
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($key);
            throw ValidationException::withMessages([
                'email' => 'Correo o contraseña incorrectos.',
            ]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        $user = Auth::user();

        // ── Redirigir según rol ────────────────────────────────────────
        if ($user?->hasRole('admin')) {
            return redirect()->intended(route('admin.pos'));
        }

        if ($user?->hasRole('vendedor') || $user?->hasRole('empleador')) {
            return redirect()->intended(route('employer.dashboard'));
        }

        // Cliente (o cualquier otro rol) → tienda
        return redirect()->intended(route('store.catalog'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
