<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserNotificationSettingsController;
use App\Http\Controllers\User\UserPrivacityController;
use Illuminate\Support\Facades\Route;

Route::middleware('userlogged')->group(function () {

    Route::apiResource('users', UserController::class);

    Route::prefix('user')->group(function () {
        Route::put('/update', [UserController::class, 'update']);
    });

    Route::prefix('user-notifications-settings')->group(function () {
        Route::get('/', [UserNotificationSettingsController::class, 'getUserNotificationsSettings']);
        Route::put('/', [UserNotificationSettingsController::class, 'saveUserNotificationsSettings']);
    });

    Route::prefix('user-privacity-settings')->group(function () {
        Route::get('/', [UserPrivacityController::class, 'getUserPrivacitySettings']);
        Route::put('/', [UserPrivacityController::class, 'saveUserPrivacitySettings']);
    });
});
