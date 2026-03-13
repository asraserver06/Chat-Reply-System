<?php

namespace App\Listeners;

use App\Events\MessageSent;

class BroadcastMessageSent
{
    public function handle(MessageSent $event): void
    {
        // Broadcasting is handled automatically because MessageSent implements
        // ShouldBroadcast. This listener just logs for debugging purposes.
        logger()->info('Message broadcast dispatched', [
            'message_id' => $event->message->id,
            'chat_id'    => $event->message->chat_id,
        ]);
    }
}
