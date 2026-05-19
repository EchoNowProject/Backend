<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Server\StoreCreateNewChannelRequest;
use App\Models\ServerChatConversation;

class ChannelController extends Controller
{
    public function createChannel(StoreCreateNewChannelRequest $request)
    {

        $conversation = ServerChatConversation::create([
            'id_server' => $request->server_id,
            'channel_text_name' => $request->channel_text_name,
        ]);

        return response()->json($conversation, 201);
    }
}
