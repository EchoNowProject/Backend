<?php

use App\Http\Controllers\StatusUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('userlogged')->group(function () {

    Route::apiResource('status-user', StatusUserController::class);

    Route::prefix('status-users')->group(function () {
        Route::get('/get-my-status', [StatusUserController::class, 'getMyStatus']);
        Route::put('/set-new-status/{idStatus}', [StatusUserController::class, 'setNewStatus']);
    });
});
