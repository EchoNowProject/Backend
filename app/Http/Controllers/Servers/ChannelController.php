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

    public function getAllChannels($idServer)
    {

        $channels = ServerChatConversation::where('id_server', (int) $idServer)->select('id', 'channel_text_name')->get();

        return response()->json([
            'channels' => $channels,
            'countChannels' => $channels->count(),
        ], 200);
    }
}
