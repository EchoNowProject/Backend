<?php

use App\Http\Controllers\Chats\ServerChatsController;
use Illuminate\Support\Facades\Route;


Route::middleware('userlogged')->prefix('server-chat')->group(function () {
    //Route::get('get-chats', [GroupsChatsController::class, 'getGroupsChats']);
    Route::get('get-messages', [ServerChatsController::class, 'getServerMessages']);
    //Route::post('create-conversation', [GroupsChatsController::class, 'createConversationIfNeccesary']);
});
