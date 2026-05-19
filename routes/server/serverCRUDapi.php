<?php

use App\Http\Controllers\Servers\ChannelController;
use App\Http\Controllers\Servers\ServerController;
use App\Http\Controllers\Servers\ServerSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('userlogged')->group(function () {

    Route::apiResource('servers', ServerController::class);

    Route::prefix('server')->group(function () {
        Route::post('invite-user', [ServerSettingsController::class, 'inviteUser']);
        Route::get('get-users-available/{id}', [ServerSettingsController::class, 'getUsersAvailable']);
    });

    Route::prefix('server')->group(function () {
        Route::post('invite-user', [ServerSettingsController::class, 'inviteUser']);
        Route::get('get-users-available/{id}', [ServerSettingsController::class, 'getUsersAvailable']);
    });

    Route::prefix('server-channel')->group(function () {
        Route::post('create-channel', [ChannelController::class, 'createChannel']);
    });
});