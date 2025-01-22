<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/clockwork', function ($request) {
    $clockwork = Clockwork\Support\Vanilla\Clockwork::init([
        'web' => [
            'enable' => true,
            'path' => DIR . '/public/vendor/clockwork',
            'uri' => '/vendor/clockwork'
        ]
    ]);

    return $clockwork->usePsrMessage($request, new Response())->returnWeb();
});
