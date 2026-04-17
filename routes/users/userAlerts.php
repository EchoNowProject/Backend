<?php

use App\Http\Controllers\User\UserAlertsController;
use Illuminate\Support\Facades\Route;


Route::middleware('userlogged')->prefix('user-alerts')->group(function () {
    Route::get('getAlerts', [UserAlertsController::class, 'getAlertsByUser']);
});
