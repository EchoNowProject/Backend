<?php

namespace App\Http\Controllers\Servers;

use App\Actions\Images\DeleteImage;
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

    /**
     * Funcion que recoge los amigos disponibles para mostrar en la lista de miembros que podemos invitar
     * @param mixed $idServer
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersAvailable($idServer)
    {
        $ownerId = Server::findOrFail($idServer)->owner_id;

        $serverMembers = ServerMember::where('server_id', (int) $idServer)
            ->pluck('user_id')
            ->toArray();

        $members = array_merge($serverMembers, [$ownerId]);

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

    /**
     * Funcion para invitar a un amigo a un servidor
     * ! Ahora lo mete a fuerza bruta (hacer que se meta en alertas del usuario y sea el quien acepte o no)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Funcion que elimina la Imagen de un Servidor
     * @param mixed $idServer
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImageServer($idServer)
    {

        $server = Server::findOrFail((int) $idServer);

        if ($server->owner_id != Auth::id())
            return response()->json('Este usuario no puede hacer cambios en el servidor', 401);

        $path = Server::IMAGESERVERPATH . $server->avatar_img;

        $deleteImage = new DeleteImage();
        if ($deleteImage->delete($path)) {
            DB::beginTransaction();
            $server->update(['avatar_img' => null]);

            DB::commit();

            return response()->json($server, 200);
        }

        return response()->json('No se ha podido eliminar la foto del servidor', 500);

    }

    /**
     * Funcion que obtiene los miembros de un servidor incluido el propietario
     * @param mixed $idServer
     * @return array
     */
    public function getMembersServer($idServer)
    {
        $server = Server::findOrFail((int) $idServer);

        $members = $server->participants()
            ->select('users.id', 'users.username', 'users.status')
            ->with('statusUser')
            ->get()
            ->toArray();

        if ($server->owner) {
            $members[] = $server->owner()
                ->select('users.id', 'users.username', 'users.status')
                ->with('statusUser')
                ->first()
                ->toArray();
        }

        return $members;
    }

    /**
     * Funcion que elimina un miembro determinado del servidor
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMemberServer(Request $request)
    {

        if (!$request->idServer && !$request->idUser)
            return response()->json('No se ha encontrado servidor o usuario', 406);

        ServerMember::where('user_id', $request->idUser)->where('server_id', $request->idServer)->delete();

        return response()->json('Miembro del servidor eliminado con exito', 200);
    }
}
