<?php

use App\Http\Controllers\Friends\FriendRequestController;
use App\Http\Controllers\HelperController;
use Illuminate\Support\Facades\Route;


Route::middleware('userlogged')->prefix('file')->group(function () {
    Route::get('download', [HelperController::class, 'downloadFileByPath']);
});
