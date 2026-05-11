<?php

use App\Http\Controllers\Chats\GroupsChatsController;
use Illuminate\Support\Facades\Route;


Route::middleware('userlogged')->prefix('groups-chat')->group(function () {
    Route::get('get-chats', [GroupsChatsController::class, 'getGroupsChats']);
    Route::get('get-messages', [GroupsChatsController::class, 'getGroupMessages']);
    Route::post('create-conversation', [GroupsChatsController::class, 'createConversationIfNeccesary']);
});
