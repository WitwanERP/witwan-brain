<?php

use App\Http\Controllers\Api\LicenciaSectionController;
use Illuminate\Support\Facades\Route;

// API v1 - Consumo externo para licencias
Route::prefix('v1')->group(function () {
    // Obtener secciones de una licencia
    Route::get('licencia/{key}/secciones', [LicenciaSectionController::class, 'getSecciones']);

    // Obtener menú estructurado
    Route::get('licencia/{key}/menu', [LicenciaSectionController::class, 'getMenu']);

    // Obtener árbol de secciones
    Route::get('licencia/{key}/arbol', [LicenciaSectionController::class, 'getArbol']);

    // Verificar permiso específico
    Route::get('licencia/{key}/permiso/{seccionKey}', [LicenciaSectionController::class, 'verificarPermiso']);
});
