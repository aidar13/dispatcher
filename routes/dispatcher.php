<?php

use App\Module\DispatcherSector\Controllers\DispatcherSectorController;
use App\Module\DispatcherSector\Controllers\SectorController;
use App\Module\DispatcherSector\Controllers\WaveController;
use Illuminate\Support\Facades\Route;

// Dispatcher Sector
Route::put('dispatcher-sectors/{id}', [DispatcherSectorController::class, 'update'])
    ->name('dispatcher-sectors.update');

Route::get('dispatcher-sectors', [DispatcherSectorController::class, 'index'])
    ->name('dispatcher-sectors.index');

Route::get('dispatcher-sectors/all', [DispatcherSectorController::class, 'getAll'])
    ->name('dispatcher-sectors.get-all');

Route::post('dispatcher-sectors', [DispatcherSectorController::class, 'store'])
    ->name('dispatcher-sectors.store');

Route::delete('dispatcher-sectors/{id}', [DispatcherSectorController::class, 'destroy'])
    ->name('dispatcher-sectors.destroy');


// Sector route
Route::get('sectors', [SectorController::class, 'index'])
    ->name('sectors.index');

Route::post('sectors', [SectorController::class, 'store'])
    ->name('sectors.store');

Route::put('sectors/{id}', [SectorController::class, 'update'])
    ->name('sectors.update');

Route::delete('sectors/{id}', [SectorController::class, 'destroy'])
    ->name('sectors.destroy');


// Wave route
Route::get('waves', [WaveController::class, 'index'])
    ->name('waves.index');

Route::get('waves/{id}/invoices', [WaveController::class, 'invoices'])
    ->name('waves.invoices');

Route::get('waves/{id}', [WaveController::class, 'show'])
    ->name('waves.show');

Route::post('waves', [WaveController::class, 'store'])
    ->name('waves.store');

Route::put('waves/{id}', [WaveController::class, 'update'])
    ->name('waves.update');

Route::delete('waves/{id}', [WaveController::class, 'destroy'])
    ->name('waves.destroy');
