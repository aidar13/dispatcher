<?php

use App\Module\Take\Controllers\OrderPeriodController;
use App\Module\Take\Controllers\OrderTakeController;
use Illuminate\Support\Facades\Route;

// order-take
Route::get('/order-take', [OrderTakeController::class, 'index'])
    ->name('order-take.index');

Route::post('/order-take/assign', [OrderTakeController::class, 'assignToCourier'])
    ->name('order-take.assign');

Route::post('/order-take/change-date', [OrderTakeController::class, 'changeTakeDateByOrderId'])
    ->name('order-take.change-date');

Route::get('/order-take/report', [OrderTakeController::class, 'orderTakeReport'])
    ->name('order-take.report');

Route::get('/order-take/{orderId}', [OrderTakeController::class, 'takeInfoByOrderId'])
    ->name('order-take.take-info-by-order-id');

Route::put('/order-take/set-status-by-invoice', [OrderTakeController::class, 'setStatusByInvoice'])
    ->name('order-take.set-status-by-invoice');

// order-period
Route::get('/order-period', [OrderPeriodController::class, 'index'])
    ->name('order-period.index');
