<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Notifications\NewMessageNotification;

class SendMessageNotification
{
    public function handle(MessageSent $event): void
    {
        $message = $event->message->loadMissing('chat.user');
        $recipient = $message->chat->user;

        // Don't notify the sender about their own message
        if ($recipient && $recipient->id !== $message->user_id) {
            // Use 'database' channel only (no mail required in dev)
            $recipient->notify(new NewMessageNotification($message));
        }
    }
}
