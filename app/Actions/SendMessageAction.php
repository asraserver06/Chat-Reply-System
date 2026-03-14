<?php

namespace App\Actions;

use App\DTOs\MessageDTO;
use App\Events\MessageSent;
use App\Jobs\ProcessAutoReply;
use App\Models\Message;
use App\Repositories\MessageRepository;
use App\Services\AutoReplyService;

class SendMessageAction
{
    public function __construct(
        private readonly MessageRepository $messageRepository,
        private readonly AutoReplyService $autoReplyService
    ) {}

    public function execute(MessageDTO $dto): Message
    {
        $message = $this->messageRepository->create($dto->toArray());

        // Fire the event – triggers broadcast + notification listener
        event(new MessageSent($message));

        // Process automated reply triggers synchronously for instant response
        if (!$message->is_auto_reply) {
            $this->autoReplyService->replyIfMatched($message);
        }

        return $message;
    }
}
