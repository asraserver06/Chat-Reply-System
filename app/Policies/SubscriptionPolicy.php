<?php

namespace App\Policies;

use App\Models\User;
use Laravel\Cashier\Subscription;

class SubscriptionPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->hasRole('Admin') ? true : null;
    }

    public function view(User $user, Subscription $subscription): bool
    {
        return $user->id === $subscription->user_id;
    }

    public function subscribe(User $user): bool
    {
        return $user->hasRole('User');
    }

    public function cancel(User $user, Subscription $subscription): bool
    {
        return $user->id === $subscription->user_id;
    }
}
