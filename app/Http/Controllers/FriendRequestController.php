<?php

namespace App\Http\Controllers;

use App\Events\FriendRequestEvent;
use App\Models\FriendRequest;
use App\Models\User;
use App\Models\UserAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendRequestController extends Controller
{

    public function searchUsers($input)
    {

        $idsExcludes = FriendRequest::where('user_id_send_request', Auth::id())->get()->pluck('user_id_receive_request');

        $input = '%' . $input . '%';

        $users = User::where('username', 'like', $input)
            ->whereNot('id', Auth::id())
            ->whereNotIn('id', $idsExcludes)->take(10)->orderBy('username', 'asc')->get();

        if (!empty($users)) {
            return response()->json($users, 200);
        }

        return response()->json('No se han encontrado usuarios', 500);
    }

    public function send(Request $request)
    {
        $userReiver =  User::findOrFail((int)$request->data['id']);

        $friendRequest = FriendRequest::create([
            'user_id_receive_request' => $userReiver->id,
            'user_id_send_request' => Auth::id(),
        ]);

        UserAlert::create([
            'source_user_id' => Auth::id(),
            'target_user_id' => $userReiver->id,
            'type' => 'friend_request',
            'message' => '¡' . $userReiver->username . " quiere ser tu amigo!",
        ]);

        broadcast(new FriendRequestEvent($friendRequest->senderUser, $userReiver->id))->toOthers();

        return response()->json('Solicitud de amistad entregada', 200);
    }
}
