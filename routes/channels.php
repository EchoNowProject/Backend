<?php

use App\Models\GroupChatConversation;
use App\Models\Server;
use App\Models\ServerChatConversation;
use App\Models\ServerMember;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('test-channel', function ($user) {
    return true; // permite acceso (para pruebas)
});

Broadcast::channel('friend-request.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('individual-chat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('group-chat.{idConversation}', function ($user, $idConversation) {

    $conversation = GroupChatConversation::find($idConversation);

    if (!$conversation) {
        return false;
    }

    return $conversation->participants()
        ->where('users.id', $user->id)
        ->exists();
});

Broadcast::channel('server-chat.{idServer}.{idConversation}', function ($user, $idServer, $idConversation) {

    $conversation = ServerChatConversation::where('id_server', $idServer)->where('id', $idConversation)->first();

    if (!$conversation) {
        return false;
    }

    $server = Server::find($idServer);

    if (!$server) {
        return false;
    }

    if ($server->owner_id === $user->id) {
        return true;
    }

    return ServerMember::where('server_id', $idServer)
        ->where('user_id', $user->id)
        ->exists();
});