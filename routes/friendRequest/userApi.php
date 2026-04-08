<?php

use App\Http\Controllers\FriendRequestController;
use Illuminate\Support\Facades\Route;


Route::middleware('userlogged')->prefix('friend-request')->group(function () {
    Route::get('search/{input}', [FriendRequestController::class, 'searchUsers']);
    Route::post('/send', [FriendRequestController::class, 'send']);
});
