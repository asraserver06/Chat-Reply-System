<?php

namespace App\Actions;

use App\DTOs\MessageDTO;
use App\Events\MessageSent;
use App\Jobs\ProcessAutoReply;
use App\Models\Message;
use App\Repositories\MessageRepository;

class SendMessageAction
{
    public function __construct(
        private readonly MessageRepository $messageRepository
    ) {}

    public function execute(MessageDTO $dto): Message
    {
        $message = $this->messageRepository->create($dto->toArray());

        // Fire the event – triggers broadcast + notification listener
        event(new MessageSent($message));

        // Dispatch background job to check for automated reply triggers
        ProcessAutoReply::dispatch($message)->onQueue('default');

        return $message;
    }
}
