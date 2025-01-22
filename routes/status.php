<?php

use App\Module\Status\Controllers\CommentTemplateController;
use App\Module\Status\Controllers\StatusTypeController;
use Illuminate\Support\Facades\Route;

//Status Types Route
Route::get('status-type', [StatusTypeController::class, 'index'])
    ->name('status-type.index');

//Comment Templates Route
Route::get('comment-template', [CommentTemplateController::class, 'index'])
    ->name('comment-template.index');
