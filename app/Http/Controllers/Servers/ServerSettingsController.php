<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\Server;
use App\Models\ServerMember;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServerSettingsController extends Controller
{

    public function getUsersAvailable($idServer)
    {
        $ownerId = Server::findOrFail($idServer)->owner_id;

        $serverMembers = ServerMember::where('server_id', (int) $idServer)->get()->pluck('user_id');

        $members = [$serverMembers, $ownerId];

        $userId = Auth::id();

        $friends = Friend::where(function ($query) use ($userId, $members) {
            $query->where('first_user_id', $userId)->whereNotIn('second_user_id', $members);
        })
            ->orWhere(function ($query) use ($userId, $members) {
                $query->where('second_user_id', $userId)->whereNotIn('first_user_id', $members);
            })
            ->get([
                'first_user_id',
                'first_user_username',
                'second_user_id',
                'second_user_username'
            ])
            ->map(function ($friend) {
                return $friend->first_user_id == Auth::id()
                    ? [
                        'id' => $friend->second_user_id,
                        'username' => $friend->second_user_username,
                    ]
                    : [
                        'id' => $friend->first_user_id,
                        'username' => $friend->first_user_username,
                    ];
            })->all();
        return response()->json($friends, 200);
    }

    public function inviteUser(Request $request)
    {
        DB::beginTransaction();
        try {
            ServerMember::create([
                'server_id' => (int) $request->data['idServer'],
                'user_id' => (int) $request->data['idFriend'],
            ]);
            DB::commit();
        } catch (Exception $error) {
            DB::rollBack();
            return response()->jsonp($error->getMessage(), 400);
        }
    }
}
