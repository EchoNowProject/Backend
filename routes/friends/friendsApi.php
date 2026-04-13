<?php

use App\Http\Controllers\Friends\FriendsController;
use Illuminate\Support\Facades\Route;


Route::middleware('userlogged')->prefix('friend')->group(function () {
    Route::post('add', [FriendsController::class, 'addNewFriend']);
});
