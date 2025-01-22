<?php

use App\Module\Routing\Controllers\RoutingController;
use Illuminate\Support\Facades\Route;

// routing
Route::post('/routing', [RoutingController::class, 'create'])
    ->name('routing.create');

Route::get('/routing/courier/{courierId}', [RoutingController::class, 'getByCourier'])
    ->name('routing.get-by-courier');
