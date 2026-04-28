<?php

use App\Http\Controllers\Chats\IndividualChatController;
use Illuminate\Support\Facades\Route;


Route::middleware('userlogged')->prefix('individual-chat')->group(function () {
    Route::post('send-message', [IndividualChatController::class, 'sendMessage']);
    Route::get('get-messages', [IndividualChatController::class, 'getUserMessages']);
});
