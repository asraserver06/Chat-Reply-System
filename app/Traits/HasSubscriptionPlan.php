<?php

namespace App\Traits;

use App\Models\SubscriptionPlan;

trait HasSubscriptionPlan
{
    /**
     * Get the current subscription plan slug for this user.
     * Falls back to 'free' when no active Cashier subscription exists.
     */
    public function currentPlanSlug(): string
    {
        $subscription = $this->subscriptions()->active()->first();

        if (! $subscription) {
            return 'free';
        }

        $plan = SubscriptionPlan::where(
            'stripe_price_id',
            $subscription->stripe_price
        )->first();

        return $plan?->slug ?? 'free';
    }

    /**
     * Check if the user is on a specific plan slug.
     */
    public function onPlan(string $slug): bool
    {
        return $this->currentPlanSlug() === $slug;
    }

    /**
     * Check whether the user has reached their message limit.
     */
    public function hasReachedMessageLimit(): bool
    {
        $plan = SubscriptionPlan::where('slug', $this->currentPlanSlug())->first();

        if (! $plan || $plan->hasUnlimitedMessages()) {
            return false;
        }

        $sent = $this->messages()->where('is_auto_reply', false)->count();

        return $sent >= $plan->message_limit;
    }
}
