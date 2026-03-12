<?php

namespace App\Events;

use
    App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Message $message
    ) {}

    /**
     * Broadcast on a private per-chat channel so only chat participants receive the event.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('chat.' . $this->message->chat_id);
    }

    /**
     * Data shape sent to the frontend via Pusher.
     */
    public function broadcastWith(): array
    {
        return [
            'id'           => $this->message->id,
            'chat_id'      => $this->message->chat_id,
            'user_id'      => $this->message->user_id,
            'body'         => $this->message->body,
            'is_auto_reply' => $this->message->is_auto_reply,
            'created_at'   => $this->message->created_at?->toISOString(),
            'user'         => $this->message->user?->only('id', 'name'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
