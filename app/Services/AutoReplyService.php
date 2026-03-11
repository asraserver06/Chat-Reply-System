<?php

namespace App\Services;

use App\Models\AutomatedReply;
use App\Models\Message;
use Illuminate\Support\Collection;

class AutoReplyService
{
    /**
     * Find all active automated replies that match the given message body.
     */
    public function findMatches(string $messageBody): Collection
    {
        return AutomatedReply::where('is_active', true)
            ->orderByDesc('priority')
            ->get()
            ->filter(fn (AutomatedReply $rule) => $rule->matches($messageBody));
    }

    /**
     * Create an auto-reply message in the same chat for the first matching rule.
     */
    public function replyIfMatched(Message $message): ?Message
    {
        $matches = $this->findMatches($message->body);

        if ($matches->isEmpty()) {
            return null;
        }

        /** @var AutomatedReply $rule */
        $rule = $matches->first();

        /** @var Message $reply */
        $reply = Message::create([
            'chat_id'       => $message->chat_id,
            'user_id'       => null,     // system reply – no user
            'body'          => $rule->reply_body,
            'is_auto_reply' => true,
        ]);

        return $reply;
    }
}
