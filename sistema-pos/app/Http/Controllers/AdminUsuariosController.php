<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUsuariosController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')
            ->where('id', '!=', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.usuarios.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::whereNotIn('nombre', ['admin'])->orderBy('nombre')->get();
        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'exists:roles,nombre'],
            'estado'   => ['required', 'in:activo,inactivo'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'estado'   => $validated['estado'],
        ]);

        $role = Role::where('nombre', $validated['role'])->first();
        $user->roles()->attach($role);

        return redirect()->route('admin.usuarios')
            ->with('status', "Usuario \"{$user->name}\" creado correctamente.");
    }

    public function edit(User $user): View
    {
        $roles = Role::whereNotIn('nombre', ['admin'])->orderBy('nombre')->get();
        return view('admin.usuarios.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'exists:roles,nombre'],
            'estado'   => ['required', 'in:activo,inactivo'],
        ]);

        $user->update([
            'name'   => $validated['name'],
            'email'  => $validated['email'],
            'estado' => $validated['estado'],
            ...($validated['password'] ? ['password' => Hash::make($validated['password'])] : []),
        ]);

        $role = Role::where('nombre', $validated['role'])->first();
        $user->roles()->sync([$role->id]);

        return redirect()->route('admin.usuarios')
            ->with('status', "Usuario \"{$user->name}\" actualizado.");
    }

    public function toggleEstado(User $user): RedirectResponse
    {
        $user->update([
            'estado' => $user->estado === 'activo' ? 'inactivo' : 'activo',
        ]);

        $accion = $user->estado === 'activo' ? 'activado' : 'desactivado';
        return back()->with('status', "Usuario \"{$user->name}\" {$accion}.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        $user->roles()->detach();
        $user->delete();
        return back()->with('status', 'Usuario eliminado correctamente.');
    }
}
