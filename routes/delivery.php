<?php

use App\Module\Delivery\Controllers\DeliveryController;
use App\Module\Delivery\Controllers\PredictionController;
use App\Module\Delivery\Controllers\RouteSheetController;
use Illuminate\Support\Facades\Route;

Route::get('/deliveries', [DeliveryController::class, 'index'])
    ->name('delivery.index');

Route::get('/deliveries/report', [DeliveryController::class, 'report'])
    ->name('delivery.report');


// Prediction route
Route::get('prediction', [PredictionController::class, 'index'])
    ->name('prediction.index');

Route::get('prediction/cars', [PredictionController::class, 'cars'])
    ->name('prediction.cars');

//RouteSheet route
Route::get('route-sheet', [RouteSheetController::class, 'index'])
    ->name('route-sheet.index');

Route::get('route-sheet/{id}', [RouteSheetController::class, 'show'])
    ->name('route-sheet.show');

Route::get('route-sheet/{id}/report', [RouteSheetController::class, 'reportById'])
    ->name('route-sheet.report');

Route::post('route-sheet/{id}/resend', [RouteSheetController::class, 'resend'])
    ->name('route-sheet.resend');

Route::post('one-c/route-sheet', [RouteSheetController::class, 'createRouteSheetFromOneC'])
    ->name('one-c.route-sheet.store');
