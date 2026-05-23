<?php

use App\Http\Controllers\Group\GroupController;
use Illuminate\Support\Facades\Route;


Route::middleware('userlogged')->prefix('groups-settings')->group(function () {
    Route::get('get-group/{idConversation}', [GroupController::class, 'getGroup']);
    Route::put('update-group/{idConversation}', [GroupController::class, 'updateGroup']);
    Route::delete('delete-image-group/{idConversation}', [GroupController::class, 'deleteImageGroup']);
    Route::delete('delete-member', [GroupController::class, 'deleteMember']);
    Route::post('invite-member', [GroupController::class, 'inviteMember']);
    Route::delete('leave-group/{idConversation}', [GroupController::class, 'leaveGroup']);
    Route::delete('delete-group/{idConversation}', [GroupController::class, 'deleteGroup']);
});
