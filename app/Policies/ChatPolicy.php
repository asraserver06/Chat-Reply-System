<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;

class ChatPolicy
{
    /**
     * Admins can do everything.
     */
    public function before(User $user, string $ability): bool|null
    {
        return $user->hasRole('Admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Chat $chat): bool
    {
        return $user->id === $chat->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('User');
    }

    public function update(User $user, Chat $chat): bool
    {
        return $user->id === $chat->user_id;
    }

    public function delete(User $user, Chat $chat): bool
    {
        return $user->id === $chat->user_id;
    }
}
