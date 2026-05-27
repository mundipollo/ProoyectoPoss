<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class EmployerClientesController extends Controller
{
    public function create(): View
    {
        $user = Auth::user();
        abort_unless($user?->isStaff(), 403);

        return view('employer.clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user?->isStaff(), 403);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $newUser = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'estado'   => 'activo',
        ]);

        // Siempre asignar rol 'cliente' — nunca admin ni vendedor
        $clienteRole = Role::where('nombre', 'cliente')->first();
        if ($clienteRole) {
            $newUser->roles()->attach($clienteRole);
        }

        return redirect()
            ->route('employer.clientes.create')
            ->with('status', "Cliente \"{$newUser->name}\" registrado correctamente.");
    }
}
