<?php

namespace App\Http\Controllers\Servers;

use App\Actions\Files\DeleteFile;
use App\Actions\Images\UpdateImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServerRequest;
use App\Models\Server;
use App\Models\ServerChatConversation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Server::where(function ($query) {
            $query->where('owner_id', Auth::id())
                ->orWhereHas('participants', function ($q) {
                    $q->where('user_id', Auth::id());
                });
        })->with('mainConversation')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServerRequest $request)
    {
        DB::beginTransaction();

        do {
            $code = 'echonow:' . Str::random(25);
        } while (Server::where('invitation_code', $code)->exists());

        try {
            $server = Server::create([
                'name' => $request->name,
                'description' => $request->description ?? null,
                'avatar_img' => $request->avatar_img ?? null,
                'owner_id' => Auth::id(),
                'invitation_code' => 'echonow:' . Str::random(),
                'type_server' => $request->type_server,
            ]);

            //Se crea la conversacion principal para ese ServerChat
            $serverConversation = ServerChatConversation::create([
                'id_server' => $server->id,
                'channel_text_name' => 'General',
                'is_main' => true
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Servidor creado correctamente',
                'server' => $server,
                'server_chat_conversation' => $serverConversation,
            ], 201);

        } catch (Exception $error) {
            DB::rollBack();
            return response()->json($error->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Server::with(['mainConversation', 'conversations'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $server = Server::findOrFail($id);

        if ($server->owner_id !== Auth::id()) {
            return response()->json(['message' => 'No tienes permisos para editar este servidor'], 403);
        }

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $request->name,
                'description' => $request->description ?? null,
                'type_server' => $request->type_server,
            ];

            if ($request->has('file_avatar_image') && !empty($request->input('file_avatar_image.base64'))) {
                $base64 = $request->input('file_avatar_image.base64');

                // Solo procesamos la imagen si contiene una coma lo cual indica que es un nuevo
                // archivo subido en formato data URI (base64) desde el frontend
                if (str_contains($base64, ',')) {
                    $newName = Str::random(20);
                    $updateImage = new UpdateImage();
                    $imageUpload = $updateImage->update($base64, Server::IMAGESERVERPATH . $newName);

                    if ($imageUpload['success']) {
                        $fileName = $newName . '.' . $imageUpload['extension'];
                        $updateData['avatar_img'] = $fileName;
                    }
                }
            }

            $server->update($updateData);

            DB::commit();

            return response()->json([
                'message' => 'Servidor actualizado correctamente',
                'server' => $server->load('mainConversation'),
            ], 200);

        } catch (Exception $error) {
            DB::rollBack();
            return response()->json($error->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $server = Server::findOrFail($id);
        $actionDeleteFile = new DeleteFile();


        if ($server) {

            if (Auth::id() != $server->owner_id) {
                return response()->json('No tienes permiso para eliminar el servidor', 401);
            }

            DB::beginTransaction();

            // Eliminacion de mensajes -> archivos
            foreach ($server->allConversations as $conversation) {

                foreach ($conversation->messages as $message) {

                    foreach ($message->filesMessage as $fileModel) {

                        if ($fileModel->path_file) {

                            Log::debug($fileModel->path_file);

                            $actionDeleteFile->delete($fileModel->path_file);
                        }
                    }

                    $message->filesMessage()->delete();
                }

                $conversation->messages()->delete();
            }

            $server->allConversations()->delete();
            $server->participants()->detach();

            if ($server->avatar_img) {
                $path = Server::IMAGESERVERPATH . $server->avatar_img;
                $actionDeleteFile->delete($path);
            }

            $server->delete();
            DB::commit();

            return response()->json('El servidor se ha eliminado con éxito', 200);
        }

        DB::rollBack();

        return response()->json('No es ha podido eliminar el sevidor. Porfavor contacte con el administrador', 500);
    }
}
