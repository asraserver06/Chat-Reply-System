<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'             => 'Free',
                'slug'             => 'free',
                'stripe_price_id'  => null,
                'price'            => 0.00,
                'message_limit'    => 50,
                'features'         => [
                    'Up to 50 messages/month',
                    '1 active chat thread',
                    'Basic automated replies',
                ],
                'is_active'        => true,
            ],
            [
                'name'             => 'Basic',
                'slug'             => 'basic',
                'stripe_price_id'  => env('STRIPE_PRICE_BASIC', 'price_basic_placeholder'),
                'price'            => 9.99,
                'message_limit'    => null,  // unlimited
                'features'         => [
                    'Unlimited messages',
                    'Unlimited chat threads',
                    'Email notifications',
                    'Priority auto-replies',
                ],
                'is_active'        => true,
            ],
            [
                'name'             => 'Pro',
                'slug'             => 'pro',
                'stripe_price_id'  => env('STRIPE_PRICE_PRO', 'price_pro_placeholder'),
                'price'            => 29.99,
                'message_limit'    => null,  // unlimited
                'features'         => [
                    'Everything in Basic',
                    'Advanced chat analytics',
                    'Custom automated reply rules',
                    'API access',
                    'Priority support',
                ],
                'is_active'        => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
