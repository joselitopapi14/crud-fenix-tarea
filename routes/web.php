<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ProductoController;

Route::get('/', function () {
    return redirect()->route('productos.index');
})->name('home');

// Rutas de exportación (deben ir antes del resource)
Route::get('productos/export/pdf', [ProductoController::class, 'exportPdf'])->name('productos.export.pdf');
Route::get('productos/export/excel', [ProductoController::class, 'exportExcel'])->name('productos.export.excel');

// Rutas de productos
Route::resource('productos', ProductoController::class);

require __DIR__.'/settings.php';
