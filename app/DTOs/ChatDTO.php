<?php

namespace App\DTOs;

class ChatDTO
{
    public function __construct(
        public readonly int    $userId,
        public readonly string $title = 'New Chat',
        public readonly bool   $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId:   $data['user_id'],
            title:    $data['title'] ?? 'New Chat',
            isActive: $data['is_active'] ?? true,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id'   => $this->userId,
            'title'     => $this->title,
            'is_active' => $this->isActive,
        ];
    }
}
