<?php

namespace App\Services;

use App\Actions\CreateChatAction;
use App\Actions\SendMessageAction;
use App\DTOs\ChatDTO;
use App\DTOs\MessageDTO;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Repositories\ChatRepository;

class ChatService
{
    public function __construct(
        private readonly CreateChatAction $createChatAction,
        private readonly SendMessageAction $sendMessageAction,
        private readonly ChatRepository $chatRepository,
    ) {}

    /**
     * Open a new chat thread for the given user.
     */
    public function openChat(User $user, string $title = 'New Chat'): Chat
    {
        $dto = new ChatDTO(userId: $user->id, title: $title);
        return $this->createChatAction->execute($dto);
    }

    /**
     * Send a message in a chat thread.
     */
    public function sendMessage(Chat $chat, User $user, string $body): Message
    {
        $dto = new MessageDTO(
            chatId: $chat->id,
            userId: $user->id,
            body:   $body,
        );

        return $this->sendMessageAction->execute($dto);
    }

    /**
     * Paginated list of chats for a user.
     */
    public function userChats(User $user, int $perPage = 15)
    {
        return $this->chatRepository->allForUser($user->id, $perPage);
    }
}
