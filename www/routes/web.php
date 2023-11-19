<?php

use App\Http\Controllers\EstateFiltersController;
use App\Http\Controllers\EstateViewsController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('estates/filters', [EstateFiltersController::class, 'get']);

Route::resource('estates', EstateViewsController::class)
    ->only('create', 'show', 'edit');

Route::post('webhook', WebhookController::class);
