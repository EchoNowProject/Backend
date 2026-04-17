<?php

use App\Http\Controllers\Friends\FriendsController;
use Illuminate\Support\Facades\Route;


Route::middleware('userlogged')->prefix('friends')->group(function () {
    Route::post('add', [FriendsController::class, 'addNewFriend']);
    Route::get('get', [FriendsController::class, 'getFriends']);
    Route::delete('delete', [FriendsController::class, 'deleteFriend']);
});
