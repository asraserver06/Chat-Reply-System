<?php

namespace App\DTOs;

class MessageDTO
{
    public function __construct(
        public readonly int    $chatId,
        public readonly int    $userId,
        public readonly string $body,
        public readonly bool   $isAutoReply = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            chatId:      $data['chat_id'],
            userId:      $data['user_id'],
            body:        $data['body'],
            isAutoReply: $data['is_auto_reply'] ?? false,
        );
    }

    public function toArray(): array
    {
        return [
            'chat_id'      => $this->chatId,
            'user_id'      => $this->userId,
            'body'         => $this->body,
            'is_auto_reply' => $this->isAutoReply,
        ];
    }
}
