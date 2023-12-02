<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstateFiltersController;
use App\Http\Controllers\EstateViewsController;
use App\Http\Controllers\UpdateEstateAsAdminController;
use App\Http\Controllers\UpdateUserEstateController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Hello World!! This is GetKeysBot Site 🤠';
});

Route::patch('estates/{estate}/user-update', [UpdateUserEstateController::class, 'get']);

Route::get('estates/filters', [EstateFiltersController::class, 'get']);

Route::get('login', [AuthController::class, 'getForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::resource('estates', EstateViewsController::class)
    ->only('create', 'show', 'edit');

Route::post('webhook', WebhookController::class);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('admin/estates/{estate}/edit', [UpdateEstateAsAdminController::class, 'edit']);
    Route::post('admin/estates/{estate}/edit', [UpdateEstateAsAdminController::class, 'update']);
});
