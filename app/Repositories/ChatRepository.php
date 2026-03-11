<?php

namespace App\Repositories;

use App\Models\Chat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ChatRepository
{
    public function allForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Chat::where('user_id', $userId)
            ->with(['messages' => fn ($q) => $q->latest()->limit(1)])
            ->latest()
            ->paginate($perPage);
    }

    public function findForUser(int $chatId, int $userId): ?Chat
    {
        return Chat::where('id', $chatId)
            ->where('user_id', $userId)
            ->with('messages.user')
            ->first();
    }

    public function all(int $perPage = 20): LengthAwarePaginator
    {
        return Chat::with(['user', 'messages'])
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Chat
    {
        return Chat::create($data);
    }

    public function delete(Chat $chat): bool
    {
        return (bool) $chat->delete();
    }
}
