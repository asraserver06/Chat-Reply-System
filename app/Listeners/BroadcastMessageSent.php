<?php

namespace App\Listeners;

use App\Events\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;

class BroadcastMessageSent implements ShouldQueue
{
    public string $queue = 'default';

    public function handle(MessageSent $event): void
    {
        // Broadcasting is handled automatically because MessageSent implements
        // ShouldBroadcast. This listener exists as an explicit hook for any
        // additional side-effects needed after the broadcast (e.g. logging).
        logger()->info('Message broadcast dispatched', [
            'message_id' => $event->message->id,
            'chat_id'    => $event->message->chat_id,
        ]);
    }
}
