<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsClient
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isCliente()) {
            return redirect()
                ->guest(route('client.login'))
                ->with('status', 'Debes iniciar sesión como cliente para usar el carrito.');
        }

        return $next($request);
    }
}
