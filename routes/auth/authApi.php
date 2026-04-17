<?php

//
// ? Para mas informacion de la Autenticacion consultar en
// ? https://jwt-auth.readthedocs.io/en/develop/
//

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['userlogged'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('update-password', [AuthController::class, 'updatePassword']);
});
