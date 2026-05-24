<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServerChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $idServer;
    public $idConversation;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $idConversation, $idServer)
    {
        $this->message = $message;
        $this->idServer = $idServer;
        $this->idConversation = $idConversation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('server-chat.' . $this->idServer . '.' . $this->idConversation),
        ];
    }

    public function broadcastAs()
    {
        return 'server-chat';
    }
}
