<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LicenciaController;
use App\Http\Controllers\SeccionController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Secciones
    Route::get('secciones', [SeccionController::class, 'index'])->name('secciones.index');
    Route::get('secciones/create', [SeccionController::class, 'create'])->name('secciones.create');
    Route::post('secciones', [SeccionController::class, 'store'])->name('secciones.store');
    Route::get('secciones/{seccion}/edit', [SeccionController::class, 'edit'])->name('secciones.edit');
    Route::put('secciones/{seccion}', [SeccionController::class, 'update'])->name('secciones.update');
    Route::delete('secciones/{seccion}', [SeccionController::class, 'destroy'])->name('secciones.destroy');
    Route::post('secciones/reorder', [SeccionController::class, 'reorder'])->name('secciones.reorder');
    Route::get('secciones/por-sistema', [SeccionController::class, 'getSeccionesPorSistema'])->name('secciones.por-sistema');

    // Licencias
    Route::get('licencias', [LicenciaController::class, 'index'])->name('licencias.index');
    Route::get('licencias/create', [LicenciaController::class, 'create'])->name('licencias.create');
    Route::post('licencias', [LicenciaController::class, 'store'])->name('licencias.store');
    Route::get('licencias/{licencia}', [LicenciaController::class, 'show'])->name('licencias.show');
    Route::get('licencias/{licencia}/edit', [LicenciaController::class, 'edit'])->name('licencias.edit');
    Route::put('licencias/{licencia}', [LicenciaController::class, 'update'])->name('licencias.update');
    Route::delete('licencias/{licencia}', [LicenciaController::class, 'destroy'])->name('licencias.destroy');
    Route::put('licencias/{licencia}/secciones', [LicenciaController::class, 'syncSecciones'])->name('licencias.sync-secciones');
    Route::post('licencias/{licencia}/toggle-seccion', [LicenciaController::class, 'toggleSeccion'])->name('licencias.toggle-seccion');
    Route::post('licencias/{licencia}/copiar-secciones', [LicenciaController::class, 'copiarSecciones'])->name('licencias.copiar-secciones');
});

// Route model binding
Route::bind('seccion', function ($value) {
    return \App\Models\Seccion::where('seccion_id', $value)->firstOrFail();
});

Route::bind('licencia', function ($value) {
    return \App\Models\Licencia::where('licencia_id', $value)->firstOrFail();
});
