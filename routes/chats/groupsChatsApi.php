<?php

use App\Http\Controllers\Chats\GroupsChatsController;
use Illuminate\Support\Facades\Route;


Route::middleware('userlogged')->prefix('groups-chat')->group(function () {
    Route::get('get-chats', [GroupsChatsController::class, 'getGroupsChats']);
    Route::post('create-conversation', [GroupsChatsController::class, 'createConversationIfNeccesary']);
});
