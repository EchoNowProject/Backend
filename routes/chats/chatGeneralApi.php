<?php

use App\Http\Controllers\Chats\ChatGeneralController;
use App\Http\Controllers\Chats\GroupsChatsController;
use Illuminate\Support\Facades\Route;


Route::middleware('userlogged')->prefix('general-chat')->group(function () {
    Route::post('send-message', [ChatGeneralController::class, 'sendMessage']);
});
