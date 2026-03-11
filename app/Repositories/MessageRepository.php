<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MessageRepository
{
    public function forChat(int $chatId, int $perPage = 30): LengthAwarePaginator
    {
        return Message::where('chat_id', $chatId)
            ->with('user')
            ->oldest()
            ->paginate($perPage);
    }

    public function create(array $data): Message
    {
        return Message::create($data);
    }

    public function markAllReadInChat(int $chatId, int $userId): void
    {
        Message::where('chat_id', $chatId)
            ->where('user_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
