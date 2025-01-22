<?php

use App\Module\CourierApp\Controllers\CarOccupancyController;
use App\Module\CourierApp\Controllers\CourierCallController;
use App\Module\CourierApp\Controllers\CourierController;
use App\Module\CourierApp\Controllers\CourierLocationController;
use App\Module\CourierApp\Controllers\CourierPaymentController;
use App\Module\CourierApp\Controllers\CourierStateController;
use App\Module\CourierApp\Controllers\DeliveryController;
use App\Module\CourierApp\Controllers\OrderTakeController;
use Illuminate\Support\Facades\Route;

// order-take
Route::get('/courier-app/order-take', [OrderTakeController::class, 'index'])
    ->name('courier-app.order-take.index');

Route::get('/courier-app/order-take/{orderId}', [OrderTakeController::class, 'show'])
    ->name('courier-app.order-take.show');

Route::post('/courier-app/order-take/save-shortcoming-files', [OrderTakeController::class, 'saveShortcomingReportFiles'])
    ->name('courier-app.order-take.save-shortcoming-files');

Route::get('/courier-app/order-take/shortcoming-files/{orderId}', [OrderTakeController::class, 'showShortcomingReportFiles'])
    ->name('courier-app.order-take.show-shortcoming-files');

Route::post('/courier-app/order-take/mass-approve', [OrderTakeController::class, 'massApproveOrderTakes'])
    ->name('courier-app.order-take.mass-approve');

Route::put('/courier-app/order-take/{id}/set-wait-list-status', [OrderTakeController::class, 'setWaitListStatus'])
    ->name('courier-app.order-take.set-wait-list-status');

Route::put('/courier-app/order-take/{invoiceId}/save-pack-code', [OrderTakeController::class, 'savePackCode'])
    ->name('courier-app.order-take.save-pack-code');

// delivery
Route::get('/courier-app/delivery', [DeliveryController::class, 'index'])
    ->name('courier-app.delivery.index');

Route::get('/courier-app/delivery/{id}', [DeliveryController::class, 'show'])
    ->name('courier-app.delivery.show');

Route::post('/courier-app/delivery/{id}/approve', [DeliveryController::class, 'approve'])
    ->name('courier-app.delivery.approve');

Route::post('/courier-app/delivery/approve-via-verification', [DeliveryController::class, 'approveViaVerification'])
    ->name('courier-app.delivery.approve-via-verification');

Route::put('/courier-app/delivery/{invoiceId}/set-wait-list-status', [DeliveryController::class, 'setWaitListStatus'])
    ->name('courier-app.delivery.set-wait-list-status');

// courier
Route::get('/courier-app/profile', [CourierController::class, 'profile'])
    ->name('courier-app.courier.profile');

Route::get('/courier-app/check-by-phone/{phone}', [CourierController::class, 'checkByPhone'])
    ->name('courier-app.courier.check-by-phone');

// courier-state
Route::post('/courier-app/order-take/here-state', [CourierStateController::class, 'orderTakeHereState'])
    ->name('courier-app.order-take.here-state');

Route::post('/courier-app/delivery/here-state', [CourierStateController::class, 'deliveryHereState'])
    ->name('courier-app.delivery.here-state');

// courier-payment
Route::post('/courier-app/order-take/courier-payment', [CourierPaymentController::class, 'saveOrderTakeCourierPaymentFiles'])
    ->name('courier-app.order-take.courier-payment');

Route::post('/courier-app/delivery/courier-payment', [CourierPaymentController::class, 'saveDeliveryCourierPaymentFiles'])
    ->name('courier-app.delivery.courier-payment');

Route::get('/courier-app/delivery/courier-payment/{invoiceId}', [CourierPaymentController::class, 'showByInvoiceId'])
    ->name('courier-app.delivery.courier-payment.show-by-invoice-id');

Route::get('/courier-app/order-take/courier-payment/{orderId}', [CourierPaymentController::class, 'showByOrderId'])
    ->name('courier-app.delivery.courier-payment.show-by-order-id');

// courier-call
Route::post('/courier-app/order-take/courier-call', [CourierCallController::class, 'orderTakeCourierCall'])
    ->name('courier-app.order-take.courier-call');

Route::post('/courier-app/delivery/courier-call', [CourierCallController::class, 'deliveryCourierCall'])
    ->name('courier-app.delivery.courier-call');

// car-occupancy
Route::post('/courier-app/order-take/car-occupancy', [CarOccupancyController::class, 'orderTakeCarOccupancy'])
    ->name('courier-app.order-take.car-occupancy');

// courier-location
Route::post('/courier-app/courier-locations', [CourierLocationController::class, 'store'])
    ->name('courier-app.courier-locations.store');
