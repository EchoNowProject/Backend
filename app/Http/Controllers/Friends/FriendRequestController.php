<?php

namespace App\Http\Controllers\Friends;

use App\Http\Controllers\Controller;
use App\Events\FriendRequestEvent;
use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\User;
use App\Models\UserAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendRequestController extends Controller
{

    /**
     * Funcion que busca los usuarios disponibles en la base de datos
     * @param mixed $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUsers($input)
    {
        $idsExcludes = [];

        // * Para evitar que el REMITENTE pueda buscar a un DESTINATARIO con solicitud enviada
        $idsExcludes = array_merge($idsExcludes, FriendRequest::where('user_id_send_request', Auth::id())->get()->pluck('user_id_receive_request')->toArray());
        // * Para evitar que el DESTINATARIO pueda buscar a un REMITENTE con solicitud enviada
        $idsExcludes = array_merge($idsExcludes, FriendRequest::where('user_id_receive_request', Auth::id())->get()->pluck('user_id_send_request')->toArray());

        $friendsExludes = Friend::where('first_user_id', Auth::id())
            ->orWhere('second_user_id', Auth::id())
            ->get()
            ->map(
                fn($friend) => $friend->first_user_id == Auth::id()
                    ? $friend->second_user_id
                    : $friend->first_user_id
            )
            ->unique()
            ->values()
            ->toArray();
        $idsExcludes = array_merge($idsExcludes, $friendsExludes);

        $input = '%' . $input . '%';

        $users = User::where('username', 'like', $input)
            ->whereNot('id', Auth::id()) // Para no salir el usaurio autenticado en la lista
            ->whereNotIn('id', $idsExcludes)
            ->take(10)->orderBy('username', 'asc')->get();

        if (!empty($users)) {
            return response()->json($users, 200);
        }

        return response()->json('No se han encontrado usuarios', 500);
    }

    /**
     * Funcion que envia una solicitud de amistad a un usuario
     * @param Request $request -> id usuario destinatario
     * @return \Illuminate\Http\JsonResponse
     */
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
            'message' => '¡' . Auth::user()->username . " quiere ser tu amigo!",
        ]);

        broadcast(new FriendRequestEvent(
            '¡Nueva solicitud de amistad de ' . $friendRequest->senderUser->username . '!',
            $userReiver->id
        ))->toOthers();

        return response()->json('Solicitud de amistad entregada', 200);
    }

    /**
     * Funcion que elimina la solicitud de amistad si no queremos que pertenezca a nuestra lista de amigos
     * @param Request $request (Object UserAlert)
     * @return \Illuminate\Http\JsonResponse
     */
    public function declineFriendRequest(Request $request)
    {
        $alert =  $request->alert;

        UserAlert::findOrFail($alert['id'])->delete();

        FriendRequest::where('user_id_send_request', $alert['source_user_id'])->first()->delete();

        return response()->json('Alerta eliminada con exito');
    }
}
