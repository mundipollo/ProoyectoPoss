<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isStaff()) {
            return redirect()
                ->route('store.catalog')
                ->with('error', 'No tienes permiso para acceder al panel de personal.');
        }

        return $next($request);
    }
}
