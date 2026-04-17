<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserNotificationSettingsController;
use App\Http\Controllers\User\UserPrivacityController;
use Illuminate\Support\Facades\Route;

Route::post('users/new', [UserController::class, 'store']);

Route::middleware('userlogged')->group(function () {

    Route::prefix('users')->group(function () {
        Route::get('{id}', [UserController::class, 'show']);
    });


    Route::prefix('user')->group(function () {
        Route::put('/update', [UserController::class, 'update']);
        Route::delete('/delete-image', [UserController::class, 'deleteUserImage']);
        Route::put('/update-image', [UserController::class, 'updateUserImage']);
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
