<?php

use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Auth\ClientRegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EmployerDashboardController;
use App\Http\Controllers\AdminPosController;
use App\Http\Controllers\AdminUsuariosController;
use App\Http\Controllers\AdminVentasController;
use App\Http\Controllers\EmployerPosController;
use App\Http\Controllers\EmployerClientesController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\StoreCatalogController;
use App\Services\CartService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('store.home', [
        'cartCount' => app(CartService::class)->count(),
    ]);
})->name('home');

Route::get('/tienda', [StoreCatalogController::class, 'index'])->name('store.catalog');

Route::prefix('cliente')->name('client.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('ingresar', [ClientAuthController::class, 'create'])->name('login');
        Route::post('ingresar', [ClientAuthController::class, 'store'])->name('login.store');
        Route::get('registro', [ClientRegisteredUserController::class, 'create'])->name('register');
        Route::post('registro', [ClientRegisteredUserController::class, 'store'])->name('register.store');
    });

    // Verificación OTP (sin middleware guest, el usuario aún no está logueado)
    Route::get('verificar',  [ClientRegisteredUserController::class, 'showVerify'])->name('verify');
    Route::post('verificar', [ClientRegisteredUserController::class, 'verify'])->name('verify.store');
    Route::post('reenviar',  [ClientRegisteredUserController::class, 'resend'])->name('verify.resend');

    Route::post('salir', [ClientAuthController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');
});

Route::middleware(['auth', 'client'])->group(function () {
    Route::get('/carrito', [CartController::class, 'index'])->name('store.cart');
    Route::post('/carrito/pagar', [CartController::class, 'pay'])->name('store.checkout.pay');
    Route::post('/carrito/agregar/{product}', [CartController::class, 'add'])->name('store.cart.add');
    Route::patch('/carrito/{product}', [CartController::class, 'update'])->name('store.cart.update');
    Route::delete('/carrito/{product}', [CartController::class, 'remove'])->name('store.cart.remove');
});

Route::get('/staff-login', function () {
    return view('auth.staff-login');
})->name('staff.login');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user?->isCliente()) {
        return redirect()->route('store.catalog');
    }

    if ($user?->hasRole('admin')) {
        return redirect()->route('admin.pos');
    }

    if ($user && ($user->hasRole('vendedor') || $user->hasRole('empleador'))) {
        return redirect()->route('employer.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'staff'])->group(function () {
    Route::get('/admin/perfil', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('/admin/pos', [AdminPosController::class, 'index'])
        ->name('admin.pos');
    Route::get('/empleador/perfil', [EmployerDashboardController::class, 'index'])
        ->name('employer.dashboard');
    Route::get('/empleador/pos',   [EmployerPosController::class, 'index'])
        ->name('employer.pos');
    Route::get('/empleador/clientes/crear', [EmployerClientesController::class, 'create'])
        ->name('employer.clientes.create');
    Route::post('/empleador/clientes',      [EmployerClientesController::class, 'store'])
        ->name('employer.clientes.store');

    Route::resource('products', ProductController::class);

    Route::get('/admin/usuarios',                    [AdminUsuariosController::class, 'index'])->name('admin.usuarios');
    Route::get('/admin/usuarios/crear',              [AdminUsuariosController::class, 'create'])->name('admin.usuarios.create');
    Route::post('/admin/usuarios',                   [AdminUsuariosController::class, 'store'])->name('admin.usuarios.store');
    Route::get('/admin/usuarios/{user}/editar',      [AdminUsuariosController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/admin/usuarios/{user}',             [AdminUsuariosController::class, 'update'])->name('admin.usuarios.update');
    Route::patch('/admin/usuarios/{user}/estado',    [AdminUsuariosController::class, 'toggleEstado'])->name('admin.usuarios.toggle');
    Route::delete('/admin/usuarios/{user}',          [AdminUsuariosController::class, 'destroy'])->name('admin.usuarios.destroy');

    Route::post('/admin/pos/vender', [AdminPosController::class, 'vender'])->name('admin.pos.vender');

    Route::get('/admin/ventas',          [AdminVentasController::class, 'index'])->name('admin.ventas');
    Route::get('/admin/ventas/{id}',     [AdminVentasController::class, 'show'])->name('admin.ventas.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
