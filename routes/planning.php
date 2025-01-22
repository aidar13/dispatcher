<?php

use App\Module\Planning\Controllers\ContainerController;
use App\Module\Planning\Controllers\ContainerInvoiceController;
use App\Module\Planning\Controllers\PlanningController;
use Illuminate\Support\Facades\Route;

// Planning route
Route::get('planning', [PlanningController::class, 'index'])
    ->name('planning.index');

Route::get('planning/couriers', [PlanningController::class, 'courierIndex'])
    ->name('planning.courier.index');

// Container route
Route::get('containers', [ContainerController::class, 'index'])
    ->name('container.index');

Route::get('containers/paginated', [ContainerController::class, 'paginated'])
    ->name('container.paginated');

Route::get('containers/invoice/{invoiceId}', [ContainerController::class, 'invoice'])
    ->name('container.invoice');

Route::get('containers/export', [ContainerController::class, 'export'])
    ->name('container.export');

Route::post('containers/generate', [ContainerController::class, 'generate'])
    ->name('container.generate');

Route::post('containers', [ContainerController::class, 'store'])
    ->name('container.create');

Route::post('container-invoices/detach', [ContainerInvoiceController::class, 'detach'])
    ->name('container.invoice.detach');

Route::delete('containers/{containerId}', [ContainerController::class, 'destroy'])
    ->name('container.destroy');

Route::post('containers/{containerId}/resend', [ContainerController::class, 'resendContainer'])
    ->name('container.resend');

Route::post('containers/{containerId}/attach-invoices', [ContainerController::class, 'attachInvoices'])
    ->name('container.attach-invoices');

Route::post('containers/change-status', [ContainerController::class, 'changeStatus'])
    ->name('container.change-status');

Route::post('containers/assign-courier', [ContainerController::class, 'assignCourier'])
    ->name('container.assign-courier');

Route::post('containers/send-assembly', [ContainerController::class, 'sendToAssembly'])
    ->name('container.send-to-assembly');
