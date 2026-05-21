<?php

namespace App\Http\Controllers\Servers;

use App\Actions\Images\UpdateImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServerRequest;
use App\Models\Server;
use App\Models\ServerChatConversation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

            if ($request->has('file_avatar_image')) {

                $newName = Str::random(20);
                $updateImage = new UpdateImage();
                $imageUpload = $updateImage->update($request['file_avatar_image']['base64'], Server::IMAGESERVERPATH . $newName);

                if ($imageUpload['success']) {
                    $fileName = $newName . '.' . $imageUpload['extension'];
                    $updateData['avatar_img'] = $fileName;
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
        //
    }
}
