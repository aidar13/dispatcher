<?php

use App\Module\File\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::post('file/upload', [FileController::class, 'upload'])
    ->name('file.upload');

Route::delete('file/{uuidHash}', [FileController::class, 'destroy'])
    ->name('file.destroy');
