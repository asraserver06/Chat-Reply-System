<?php

namespace App\Actions;

use App\DTOs\ChatDTO;
use App\Models\Chat;
use App\Repositories\ChatRepository;

class CreateChatAction
{
    public function __construct(
        private readonly ChatRepository $chatRepository
    ) {}

    public function execute(ChatDTO $dto): Chat
    {
        return $this->chatRepository->create($dto->toArray());
    }
}
