<?php

use App\Module\Courier\Controllers\Admin\CourierController;
use App\Module\Courier\Controllers\Admin\CourierReportController;
use App\Module\Courier\Controllers\Admin\CourierScheduleController;
use App\Module\Courier\Controllers\Admin\CourierScheduleTypeController;
use Illuminate\Support\Facades\Route;

// Courier
Route::get('couriers', [CourierController::class, 'index'])
    ->name('couriers.index');

Route::get('couriers/take-list', [CourierController::class, 'takeList'])
    ->name('couriers.take-list');

Route::get('couriers/export', [CourierController::class, 'export'])
    ->name('couriers.export');

Route::get('couriers/{id}', [CourierController::class, 'show'])
    ->name('couriers.show');

Route::put('couriers/{id}', [CourierController::class, 'update'])
    ->name('couriers.update');

Route::post('couriers/{id}/upload-document', [CourierController::class, 'uploadDocument'])
    ->name('courier.upload-document');

Route::put('couriers/{id}/set-phone', [CourierController::class, 'updatePhoneNumber'])
    ->name('courier.set-phone');

Route::put('couriers/{id}/routing', [CourierController::class, 'updateRouting'])
    ->name('courier.update-routing');

// Courier Schedules Type
Route::get('courier-schedule-types', [CourierScheduleTypeController::class, 'index'])
    ->name('courier-schedule-types.index');

// Courier Schedules
Route::post('courier-schedule', [CourierScheduleController::class, 'store'])
    ->name('courier-schedule.store');

Route::get('courier-schedule/{courierId}', [CourierScheduleController::class, 'show'])
    ->name('courier-schedule.show');

// Courier End Of The Day
Route::get('courier-report/end-of-day', [CourierReportController::class, 'index'])
    ->name('courier.end-of-day.index');

Route::get('courier-report/{courierId}/end-of-day', [CourierReportController::class, 'show'])
    ->name('courier.end-of-day.show');

Route::post('courier-report/{courierId}/close-day', [CourierReportController::class, 'closeDay'])
    ->name('courier.close-day');
