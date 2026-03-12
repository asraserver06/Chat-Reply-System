<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService
    ) {}

    /** Show all available plans */
    public function index(): View
    {
        $plans        = $this->subscriptionService->allPlans();
        $user         = auth()->user();
        $currentPlan  = $user->currentPlanSlug();
        $subscription = $user->subscription('default');

        return view('user.subscription.index', compact('plans', 'currentPlan', 'subscription'));
    }

    /** Subscribe or switch plan */
    public function subscribe(Request $request): RedirectResponse
    {
        $request->validate([
            'plan_id'        => 'required|exists:subscription_plans,id',
            'payment_method' => 'required_unless:plan,free|string',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $user = auth()->user();

        try {
            $this->subscriptionService->subscribe($user, $plan, $request->payment_method ?? '');
            return back()->with('success', "Subscribed to {$plan->name} plan!");
        } catch (\Exception $e) {
            return back()->with('error', 'Subscription failed: ' . $e->getMessage());
        }
    }

    /** Cancel the current subscription */
    public function cancel(): RedirectResponse
    {
        $this->subscriptionService->cancel(auth()->user());
        return back()->with('success', 'Subscription cancelled. You retain access until end of billing period.');
    }

    /** Resume a cancelled subscription */
    public function resume(): RedirectResponse
    {
        $this->subscriptionService->resume(auth()->user());
        return back()->with('success', 'Subscription resumed!');
    }
}
