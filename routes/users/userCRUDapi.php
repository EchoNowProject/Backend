<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::get('/profile', function () {
        /* make something 🚀  */
    });
});
