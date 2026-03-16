<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserNotificationSettingsController;
use App\Http\Middleware\EnsureUserLoggued;
use Illuminate\Http\Request;
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
});
