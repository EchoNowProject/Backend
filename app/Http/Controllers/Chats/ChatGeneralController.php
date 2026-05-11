<?php

namespace App\Http\Controllers\Chats;

use App\Actions\Chats\ChatActions;
use App\Actions\Files\UpdateFile;
use App\Events\IndividualChatEvent;
use App\Http\Controllers\Controller;
use App\Models\GroupChatConversationParticipant;
use App\Models\GroupChatMessage;
use App\Models\GroupChatMessagesFile;
use App\Models\IndividualChatConversationParticipant;
use App\Models\IndividualChatMessage;
use App\Models\IndividualChatMessagesFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatGeneralController extends Controller
{
    protected $baseUrlFiles = null;

    /**
     * Funcion que envia un mensaje y lo guarda en la tabla que corresponda segun el tipo de conversacion
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        if (!$request->data['typeConversation']) {
            return response()->json('No se ha encontrado el tipo de conversacion actual', 404);
        }

        if (!$request->data['conversationId']) {
            return response()->json('No se ha encontrado el ID de la conversacion actual', 404);
        }

        $idConversation = (int) $request->data['conversationId'];

        switch ($request->data['typeConversation']) {
            case 'IndividualChat':
                $this->baseUrlFiles = "/messages/indivualChat/$idConversation/";
                return $this->saveMessageIndividalChat($request, $idConversation);

            case 'Group':
                $this->baseUrlFiles = "/messages/groupsChat/$idConversation/";
                return $this->saveMessageGroupChat($request, $idConversation);

            case 'Server':
                # code...
                break;

            default:
                return response()->json('No se ha encontrado el tipo de conversacion actual', 404);
        }
    }

    /**
     * Guarda el Mensaje en un Chat Individual
     * @param Request $request
     * @param int $idConversation
     * @return \Illuminate\Http\JsonResponse
     */
    private function saveMessageIndividalChat(Request $request, int $idConversation)
    {

        $message = IndividualChatMessage::create([
            'conversation_id' => $idConversation,
            'user_sender_id' => Auth::id(),
            'content' => $request->data['message'] ?? null,
            'has_file' => $request->data['files'] != null ? true : false,
            'type_msg' => ChatActions::setTypeMessage($request->data['message'], $request->data['files']),
        ]);

        if ($request->data['files'] != null) {
            $fileSaved = $this->uploadFiles($request->data['files']);

            foreach ($fileSaved as $file) {
                if ($file['success'] == true)
                    IndividualChatMessagesFile::create([
                        'message_id' => $message->id,
                        'file_name' => $file['file_name'],
                        'path_file' => $file['path'],
                    ]);
            }

            // Cargamos la relación para que el frontend reciba los archivos adjuntos
            $message->load('filesMessage');
        }

        $friendId = IndividualChatConversationParticipant::where('conversation_id', $idConversation)
            ->where('user_id', '!=', Auth::id())
            ->first()
            ->user_id;

        // Se lanza evento al websocket
        broadcast(new IndividualChatEvent($message, $friendId))->toOthers();

        return response()->json($message, 200);
    }

    /**
     * Guarda el Mensaje en un Chat Grupal
     * @param Request $request
     * @param int $idConversation
     * @return \Illuminate\Http\JsonResponse
     */
    private function saveMessageGroupChat(Request $request, int $idConversation)
    {

        $message = GroupChatMessage::create([
            'conversation_id' => $idConversation,
            'user_sender_id' => Auth::id(),
            'content' => $request->data['message'] ?? null,
            'has_file' => $request->data['files'] != null ? true : false,
            'type_msg' => ChatActions::setTypeMessage($request->data['message'], $request->data['files']),
        ]);

        if ($request->data['files'] != null) {
            $fileSaved = $this->uploadFiles($request->data['files']);

            foreach ($fileSaved as $file) {
                if ($file['success'] == true)
                    GroupChatMessagesFile::create([
                        'message_id' => $message->id,
                        'file_name' => $file['file_name'],
                        'path_file' => $file['path'],
                    ]);
            }

            // Cargamos la relación para que el frontend reciba los archivos adjuntos
            $message->load('filesMessage');
        }

        $friendId = GroupChatConversationParticipant::where('conversation_id', $idConversation)
            ->where('user_id', '!=', Auth::id())
            ->first()
            ->user_id;

        // Se lanza evento al websocket
        //!crear una nuevo
        //broadcast(new IndividualChatEvent($message, $friendId))->toOthers();

        return response()->json($message, 200);
    }

    //-----------------------------Funciones privadas-----------------------------


    /**
     * Funcion que se utiliza para subir archivos a una ruta indicada
     * @param array $files
     * @return array
     */
    private function uploadFiles(array $files): array
    {
        $filesSaved = [];

        foreach ($files as $file) {
            $action = new UpdateFile();
            $fileData = $action->update($file['base64'], $this->baseUrlFiles, $file['name']);

            array_push($filesSaved, $fileData);
        }

        return $filesSaved;
    }
}
