<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\View\View;

class SubscriptionManagementController extends Controller
{
    public function index(): View
    {
        // Custom subscription plans catalogue
        $plans = SubscriptionPlan::all();

        // Users who have Cashier (Stripe) subscriptions
        $subscribers = User::whereHas('subscriptions')
            ->with('subscriptions')
            ->latest()
            ->paginate(20);

        return view('admin.subscriptions.index', compact('plans', 'subscribers'));
    }
}
