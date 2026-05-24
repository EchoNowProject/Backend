<?php

namespace App\Http\Controllers\Group;

use App\Actions\Files\DeleteFile;
use App\Actions\Images\UpdateImage;
use App\Http\Controllers\Controller;
use App\Models\GroupChatConversation;
use App\Models\GroupChatConversationParticipant;
use App\Models\User;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Str;

class GroupController extends Controller
{

    /**
     * Funcion que recoge el grupo con los participantes
     * @param mixed $idConversation
     * @return GroupChatConversation
     */
    public function getGroup($idConversation)
    {
        return GroupChatConversation::with([
            'participants:id,username,status',
            'participants.statusUser:id,name'
        ])->findOrFail((int) $idConversation);
    }

    /**
     * Funcion que actualiza los campos del grupo
     * @param mixed $idConversation
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateGroup($idConversation, Request $request)
    {
        if (!$request->group_name)
            return response()->json('No se puede poner el campo del nombre de grupo vacio', 500);


        $conversation = GroupChatConversation::where('id', $idConversation)
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        try {
            DB::beginTransaction();

            $updateData = [
                'group_name' => $request->group_name,
                'description' => $request->description ?? null,
            ];

            // Comprobacion para actualizar imagenes en caso de que tengamos
            if ($request->has('file_cover_image') && !empty($request->input('file_cover_image.base64'))) {
                $base64 = $request->input('file_cover_image.base64');

                if (str_contains($base64, ',')) {
                    $newName = Str::random(20);
                    $updateImage = new UpdateImage();
                    $imageUpload = $updateImage->update($base64, GroupChatConversation::IMAGEGROUPPATH . $newName);

                    if ($imageUpload['success']) {
                        $fileName = $newName . '.' . $imageUpload['extension'];
                        $updateData['path_cover_image'] = $fileName;
                    }
                }
            }

            $conversation->update($updateData);

            DB::commit();

            return response()->json($conversation, 200);

        } catch (Exception $error) {
            DB::rollBack();
            return response()->json($error->getMessage(), 500);
        }
    }

    public function deleteImageGroup($id)
    {
        DB::beginTransaction();
        $conversation = GroupChatConversation::where('id', (int) $id)
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        if ($conversation->path_cover_image) {
            $path = GroupChatConversation::IMAGEGROUPPATH . $conversation->path_cover_image;
            $deleteImage = new DeleteFile();
            if ($deleteImage->delete($path)) {
                $conversation->update(['path_cover_image' => null]);
                DB::commit();
                return response()->json($conversation, 200);
            }
        }

        return response()->json('No se ha podido eliminar la imagen', 500);
    }

    /**
     * Eliminar a un miembro del grupo
     */
    public function deleteMember(Request $request)
    {
        $idGroup = $request->input('idGroup');
        $idUser = $request->input('idUser');

        if (!$idGroup || !$idUser) {
            return response()->json('Datos insuficientes', 400);
        }

        // Comprobar que el usuario autenticado pertenece a este grupo
        GroupChatConversation::where('id', $idGroup)
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        // Eliminar al participante
        GroupChatConversationParticipant::where('conversation_id', $idGroup)
            ->where('user_id', $idUser)
            ->delete();

        return response()->json('Miembro eliminado del grupo con éxito', 200);
    }

    /**
     * Invitar/añadir a un amigo al grupo
     */
    public function inviteMember(Request $request)
    {
        $idGroup = $request->input('idGroup');
        $idUser = $request->input('idUser');

        if (!$idGroup || !$idUser) {
            return response()->json('Datos insuficientes', 400);
        }

        // Comprobar que el usuario autenticado pertenece a este grupo
        GroupChatConversation::where('id', $idGroup)
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        // Comprobar si el usuario ya es miembro
        $exists = GroupChatConversationParticipant::where('conversation_id', $idGroup)
            ->where('user_id', $idUser)
            ->exists();

        if ($exists) {
            return response()->json('El usuario ya es miembro de este grupo', 400);
        }

        $user = User::findOrFail($idUser);

        GroupChatConversationParticipant::create([
            'conversation_id' => $idGroup,
            'user_id' => $idUser,
            'username' => $user->username,
            'joined_at' => now(),
            'avatar_image' => $user->avatar_img ?? null,
        ]);

        return response()->json('Amigo invitado con éxito', 200);
    }

    /**
     * Salir del grupo
     */
    public function leaveGroup($idConversation)
    {
        $userId = Auth::id();

        // Comprobar que el usuario pertenece al grupo
        $participant = GroupChatConversationParticipant::where('conversation_id', $idConversation)
            ->where('user_id', $userId)
            ->firstOrFail();

        $participant->delete();

        // Si ya no quedan participantes, eliminar la conversación
        $participantsCount = GroupChatConversationParticipant::where('conversation_id', $idConversation)->count();
        if ($participantsCount === 0) {
            $conversation = GroupChatConversation::findOrFail($idConversation);
            if ($conversation->path_cover_image) {
                $path = GroupChatConversation::IMAGEGROUPPATH . $conversation->path_cover_image;
                $deleteImage = new DeleteFile();
                $deleteImage->delete($path);
            }
            $conversation->delete();
        }

        return response()->json('Has salido del grupo con éxito', 200);
    }

    /**
     * Eliminar/disolver el grupo definitivamente
     */
    public function deleteGroup($idConversation)
    {
        // Comprobar que el usuario autenticado pertenece a este grupo
        $conversation = GroupChatConversation::where('id', $idConversation)
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        // Eliminar imagen de portada
        if ($conversation->path_cover_image) {
            $path = GroupChatConversation::IMAGEGROUPPATH . $conversation->path_cover_image;
            $deleteImage = new DeleteFile();
            $deleteImage->delete($path);
        }

        // Eliminar todos los participantes
        GroupChatConversationParticipant::where('conversation_id', $idConversation)->delete();

        // Eliminar la conversación
        $conversation->delete();

        return response()->json('El grupo ha sido eliminado con éxito', 200);
    }
}
