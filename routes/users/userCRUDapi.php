<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);

Route::prefix('user')->group(function () {
    Route::put('/update', [UserController::class, 'update']);
});
