<?php

namespace App\Http\Controllers\Friends;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\UserAlert;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendsController extends Controller
{

    /**
     * Funcion que añade un usuario a nuestra lista de amigos a traves de una solicitud de amistad
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNewFriend(Request $request)
    {
        $idAlert = (int)$request->data['idAlert'];

        DB::beginTransaction();
        try {
            $alert = UserAlert::with(['sourceUser:id,username', 'targetUser:id,username'])->findOrFail($idAlert);

            /* Crear la relacion de amigos */
            Friend::create([
                'first_user_id' => $alert->sourceUser->id,
                'first_user_username' => $alert->sourceUser->username,
                'second_user_id' => $alert->targetUser->id,
                'second_user_username' => $alert->targetUser->username,
            ]);

            /* Eliminar las uniones de solicitudes de amistad */
            FriendRequest::where('user_id_send_request', $alert->source_user_id)->where('user_id_receive_request', $alert->target_user_id)->firstOrFail()->delete();

            $alert->delete();

            DB::commit();
            return response()->json('¡' . $alert->sourceUser->username . ' ya es tu amigo!', 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json('No se ha podido aceptar la solicitud de seguimiento', 500);
        }
    }

    /**
     * Funcion que recoge los amigos de el usuario autenticado
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFriends()
    {

        $friends = Friend::where('second_user_id', Auth::id())
            ->orWhere('first_user_id', Auth::id())
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

    /**
     * Funcion que elimna un amigo para nuestro usuario autenticado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFriend(Request $request)
    {
        $idFriend = (int)$request->idFriend;
        $usernameFriend = $request->usernameFriend;

        DB::beginTransaction();
        try {
            Friend::where(function ($q) use ($idFriend) {
                $q->where('first_user_id', Auth::id())
                    ->where('second_user_id', $idFriend);
            })->orWhere(function ($q) use ($idFriend) {
                $q->where('first_user_id', $idFriend)
                    ->where('second_user_id', Auth::id());
            })->firstOrFail()->delete();
            DB::commit();
            return response()->json($usernameFriend . ' ya no es tu amigo :(', 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json('No se ha podido eliminar el amigo ' . $usernameFriend, 500);
        }
    }
}
