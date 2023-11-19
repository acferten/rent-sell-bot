<?php

use App\Http\Controllers\EstateController;
use App\Http\Controllers\EstateFiltersController;
use App\Http\Controllers\EstateLocationsController;
use Illuminate\Support\Facades\Route;
use Nutgram\Laravel\Middleware\ValidateWebAppData;

Route::post('estates/filters', [EstateFiltersController::class, 'store'])
    ->middleware(ValidateWebAppData::class);;

Route::resource('estates', EstateController::class)->only('store', 'update')
    ->middleware(ValidateWebAppData::class);

Route::post('estates/{estate}/report');

Route::get('countries/{country}/states/{state}/counties/{county}/towns', [EstateLocationsController::class, 'towns']);
Route::get('countries/{country}/states/{state}/counties', [EstateLocationsController::class, 'counties']);
Route::get('countries/{country}/states', [EstateLocationsController::class, 'states']);
Route::get('countries', [EstateLocationsController::class, 'countries']);
