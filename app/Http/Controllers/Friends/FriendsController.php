<?php

namespace App\Http\Controllers\Friends;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\UserAlert;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json('No se ha podido aceptar la solicitud de seguimiento', 500);
        }
    }
}
