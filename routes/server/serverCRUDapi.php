<?php

use App\Http\Controllers\Servers\ServerController;
use Illuminate\Support\Facades\Route;

Route::middleware('userlogged')->apiResource('servers', ServerController::class);

Route::prefix('server')->group(function () {
    // Para mas rutas de los servidores
});
