<?php

use App\Module\Monitoring\Controllers\Admin\MonitoringController;
use Illuminate\Support\Facades\Route;

// Monitoring
Route::get('monitoring/deliveries', [MonitoringController::class, 'deliveries'])
    ->name('monitoring.deliveries');

Route::get('monitoring/order-takes', [MonitoringController::class, 'orderTakes'])
    ->name('monitoring.order-takes');

Route::get('monitoring/couriers', [MonitoringController::class, 'couriers'])
    ->name('monitoring.couriers');
