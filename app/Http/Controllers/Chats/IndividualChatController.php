<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IndividualChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        //! Comprobar que todavoa son amigos
        //! Terminar

        $conversation = Conversation::where('type_conversation', 'private')
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereHas('participants', function ($query) use ($request) {
                $query->where('user_id', $request->data['friendId']);
            })
            ->first();

        if (!$conversation) {

            $participants = [Auth::id(), $request->data['friendId']];

            $conversation = Conversation::create([
                'type_conversation' => 'private'
            ]);


            foreach ($participants as $idParticipant) {

                $user = User::findOrFail($idParticipant);

                ConversationParticipant::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $idParticipant,
                    'joined_at' => Carbon::now(),
                    'avatar_image' => $user->avatar_img ?? null
                ]);
            }
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_sender_id' => Auth::id(),
            'content' => $request->data['message'],
        ]);


        return response()->json($message, 200);
    }

    public function getUserMessages()
    {
        // Recoger el user 1 y el user 2 para poner la foto y no llamar a la relacion de messages

        $conversationParticipants = ConversationParticipant::where('user_id', Auth::id())
            ->with('conversation.messages')
            ->first();

        $userInvolved = ConversationParticipant::where('conversation_id', $conversationParticipants->conversation_id)->whereNot('user_id', Auth::id())->first();

        //$usersInvolved = ConversationParticipant::where('conversation_id', $conversationParticipants->conversation_id)->whereNot('conversation_id', Auth::id())->with('user')->first()->pluck('user');

        return response()->json([
            'messages' => $conversationParticipants->conversation->messages,
            'userInvolved' => $userInvolved,
        ], 200);
    }
}
