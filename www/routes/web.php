<?php

use App\Http\Controllers\EstateController;
use App\Http\Controllers\EstateFiltersController;
use App\Http\Controllers\EstateViewsController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('estate/filters', [EstateFiltersController::class, 'get']);
Route::post('estate/filters', [EstateFiltersController::class, 'store']);

Route::resource('estate', EstateController::class)->only('store', 'update');
Route::resource('estate', EstateViewsController::class)->only('create', 'show', 'edit');

Route::post('webhook', WebhookController::class);
