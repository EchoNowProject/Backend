<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Server\StoreCreateNewChannelRequest;
use App\Models\ServerChatConversation;
use App\Models\ServerChatMessage;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function createChannel(StoreCreateNewChannelRequest $request)
    {

        $conversation = ServerChatConversation::create([
            'id_server' => $request->server_id,
            'channel_text_name' => ucfirst($request->channel_text_name),
        ]);

        return response()->json($conversation, 201);
    }

    public function getAllChannels($idServer)
    {

        $channels = ServerChatConversation::where('id_server', (int) $idServer)->select('id', 'channel_text_name', 'is_main')->get();

        return response()->json([
            'channels' => $channels,
            'countChannels' => $channels->count(),
        ], 200);
    }

    public function updateChannel(Request $request, $id)
    {
        if (!$request->channel_text_name)
            return response()->json('No se puede actualizar el canal', 500);

        $channel = ServerChatConversation::findOrFail($id);
        $channel->update([
            'channel_text_name' => ucfirst($request->channel_text_name),
        ]);

        return response()->json($channel, 200);
    }

    public function deleteChannel($idChannel)
    {
        if (!$idChannel)
            return response()->json('No se puede eliminar el canal', 401);

        $channel = ServerChatConversation::findOrFail($idChannel);

        $channel->messages()->delete();

        $channel->delete();

        return response()->json('Canal eliminado con exito', 200);
    }
}
