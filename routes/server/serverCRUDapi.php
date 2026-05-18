<?php

use App\Http\Controllers\Servers\ServerController;
use App\Http\Controllers\Servers\ServerSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('userlogged')->apiResource('servers', ServerController::class);

Route::prefix('server')->group(function () {
    Route::post('invite-user', [ServerSettingsController::class, 'inviteUser']);
    Route::get('get-users-available/{id}', [ServerSettingsController::class, 'getUsersAvailable']);
});
