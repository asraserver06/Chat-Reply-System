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

        $replyBody = "I'm sorry, I couldn't understand that. Please wait for a human representative or try asking something else.";

        // If we found rules that matched the keyword, override the fallback with the highest priority rule.
        if ($matches->isNotEmpty()) {
            $rule = $matches->first();
            $replyBody = $rule->reply_body;
        }

        $reply = Message::create([
            'chat_id'       => $message->chat_id,
            'user_id'       => null,     // system reply – no user
            'body'          => $replyBody,
            'is_auto_reply' => true,
        ]);

        // Fire event so the auto-reply is broadcasted to the frontend in real-time
        event(new \App\Events\MessageSent($reply));

        return $reply;
    }
}
