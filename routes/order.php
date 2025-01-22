<?php

use App\Module\Order\Controllers\FastDeliveryController;
use App\Module\Order\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

// Invoice
Route::put('invoices/{id}/delivery-date', [InvoiceController::class, 'updateDeliveryDate'])
    ->name('invoices.update-delivery-date');

Route::put('invoices/{id}/wave', [InvoiceController::class, 'updateWave'])
    ->name('invoices.update-wave');

Route::put('invoices/set-wave', [InvoiceController::class, 'updateWaves'])
    ->name('invoices.set-wave');

Route::get('invoices/on-hold', [InvoiceController::class, 'invoicesOnHold'])
    ->name('invoices.on-hold');

Route::get('invoices/{invoiceId}/problems', [InvoiceController::class, 'getProblems'])
    ->name('invoices.problems');

Route::post('invoices/{invoiceId}/resend-status-onec', [InvoiceController::class, 'resendStatusToOneC'])
    ->name('invoices.resend-status-onec');

// FastDeliveryOrder
Route::put('fast-delivery-orders/{internalId}/set-courier', [FastDeliveryController::class, 'assignCourier'])
    ->name('fast-delivery-order.set-courier');
