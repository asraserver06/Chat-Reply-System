<?php

namespace App\Services;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Cashier\Exceptions\IncompletePayment;

class SubscriptionService
{
    /**
     * Get all active subscription plans.
     */
    public function allPlans(): Collection
    {
        return SubscriptionPlan::where('is_active', true)
            ->orderBy('price')
            ->get();
    }

    /**
     * Subscribe a user to a plan using Stripe Cashier.
     *
     * @throws IncompletePayment
     */
    public function subscribe(User $user, SubscriptionPlan $plan, string $paymentMethod): void
    {
        if ($plan->isFree()) {
            // Cancel any active paid subscription when reverting to free
            $user->subscription('default')?->cancel();
            return;
        }

        $user->newSubscription('default', $plan->stripe_price_id)
            ->create($paymentMethod);
    }

    /**
     * Cancel the user's active subscription.
     */
    public function cancel(User $user): void
    {
        $user->subscription('default')?->cancel();
    }

    /**
     * Resume a cancelled subscription within the grace period.
     */
    public function resume(User $user): void
    {
        $user->subscription('default')?->resume();
    }
}
