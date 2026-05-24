<?php

use App\Http\Controllers\Servers\ChannelController;
use App\Http\Controllers\Servers\ServerController;
use App\Http\Controllers\Servers\ServerSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('userlogged')->group(function () {

    Route::apiResource('servers', ServerController::class);

    Route::prefix('server')->group(function () {
        Route::get('get-users-available/{id}', [ServerSettingsController::class, 'getUsersAvailable']);
        Route::get('members/{id}', [ServerSettingsController::class, 'getMembersServer']);
        Route::post('invite-user', [ServerSettingsController::class, 'inviteUser']);
        Route::delete('delete-image/{id}', [ServerSettingsController::class, 'deleteImageServer']);
        Route::delete('delete-member', [ServerSettingsController::class, 'deleteMemberServer']);
    });

    Route::prefix('server-channels')->group(function () {
        Route::post('create-channel', [ChannelController::class, 'createChannel']);
        Route::get('get-all/{idServer}', [ChannelController::class, 'getAllChannels']);
        Route::put('update-channel/{id}', [ChannelController::class, 'updateChannel']);
        Route::delete('delete/{id}', [ChannelController::class, 'deleteChannel']);
    });
});