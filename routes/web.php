<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', ['title' => 'Inicio']);
    })->name('dashboard');

    // Consultas
    Route::get('/consulta/productos', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Consulta - Productos farmacéuticos']);
    })->name('consulta.productos');

    // Mantenimiento (todas las rutas definidas para el menú; vistas en desarrollo)
    Route::get('/mantenimiento/clientes', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Mantenimiento - Cliente / Laboratorio']);
    })->name('mantenimiento.clientes.index');
    Route::get('/mantenimiento/productos', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Mantenimiento - Producto']);
    })->name('mantenimiento.productos.index');
    Route::get('/mantenimiento/categorias', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Mantenimiento - Forma farmacéutica']);
    })->name('mantenimiento.categorias.index');
    Route::get('/mantenimiento/presentaciones', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Mantenimiento - Presentación']);
    })->name('mantenimiento.presentaciones.index');
    Route::get('/mantenimiento/usuarios', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Mantenimiento - Usuario']);
    })->name('mantenimiento.usuarios.index');
    Route::get('/mantenimiento/sintomas', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Mantenimiento - Síntomas']);
    })->name('mantenimiento.sintomas.index');
    Route::get('/mantenimiento/lotes', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Mantenimiento - Lote']);
    })->name('mantenimiento.lotes.index');

    // Ventas
    Route::get('/ventas', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Ventas']);
    })->name('ventas.index');
    Route::get('/ventas/consulta', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Consulta ventas']);
    })->name('ventas.consulta.index');
    Route::get('/ventas/tickets', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Consulta tickets']);
    })->name('ventas.tickets.index');
    Route::get('/ventas/nota-credito', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Nota de crédito']);
    })->name('notacredito.index');

    // Caja
    Route::get('/caja/apertura', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Apertura de caja']);
    })->name('caja.apertura');
    Route::get('/caja/cierre', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Cierre de caja']);
    })->name('caja.cierre');
    Route::get('/caja/seguimiento', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Seguimiento de caja']);
    })->name('caja.seguimiento');

    // Reportes
    Route::get('/reportes/cuadrecaja', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Cuadre de caja']);
    })->name('reportes.cuadrecaja');
    Route::get('/reportes/ventas-dia', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Rpt. Ventas del día']);
    })->name('reportes.ventas.dia');
    Route::get('/reportes/ventas', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Rpt. Ventas']);
    })->name('reportes.ventas.rango');
    Route::get('/reportes/ingresos-por-pago', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Ingresos por tipo de pago']);
    })->name('reportes.ingresos-por-pago');
    Route::get('/reportes/compras', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Rpt. Compras']);
    })->name('reportes.compras.rango');
    Route::get('/reportes/compras-dia', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Rpt. Compras del día']);
    })->name('reportes.compras.dia');

    // Compras (solo admin, pero ruta definida para menú)
    Route::get('/compras', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Compras']);
    })->name('compras.create');
    Route::get('/compras/consulta', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Consulta compras']);
    })->name('compras.consulta.index');

    // Backup y Configuración
    Route::get('/backup', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Backup']);
    })->name('backup.index');
    Route::get('/configuracion', function () {
        return Inertia::render('EnDesarrollo', ['title' => 'Configuración']);
    })->name('configuracion.index');

    // Perfil
    Route::get('/profile', function () {
        return Inertia::render('Profile', ['title' => 'Perfil']);
    })->name('profile');
});
