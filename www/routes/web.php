<?php

use App\Http\Controllers\EstateController;
use App\Http\Controllers\EstateFiltersController;
use App\Http\Controllers\EstateLocationsController;
use App\Http\Controllers\EstateViewsController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use Nutgram\Laravel\Middleware\ValidateWebAppData;


Route::get('estate/countries/{country}/states/{state}/counties/{county}/towns', [EstateLocationsController::class, 'towns']);
Route::get('estate/countries/{country}/states/{state}/counties', [EstateLocationsController::class, 'counties']);
Route::get('estate/countries/{country}/states', [EstateLocationsController::class, 'states']);
Route::get('estate/countries', [EstateLocationsController::class, 'countries']);

Route::get('estate/filters', [EstateFiltersController::class, 'get']);
Route::post('estate/filters', [EstateFiltersController::class, 'store']);

Route::resource('estate', EstateViewsController::class)->only('create', 'show', 'edit');
Route::resource('estate', EstateController::class)->only('store', 'update')
    ->middleware(ValidateWebAppData::class);

Route::post('estate/{estate}/report');

Route::post('webhook', WebhookController::class);
