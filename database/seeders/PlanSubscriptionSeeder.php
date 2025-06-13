<?php

namespace Database\Seeders;

use App\Models\PlanSubscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'subscription_tier' => 'Basic',
                'subscription_price' => 49.00,
                'product_limit' => 500, // Limit for Basic plan
                'monthly' => 1
            ],
            [
                'subscription_tier' => 'Pro',
                'subscription_price' => 99.00,
                'product_limit' => 2500, // Limit for Pro plan
                'monthly' => 1
            ],
            [
                'subscription_tier' => 'Ultimate',
                'subscription_price' => 129.00,
                'product_limit' => null, // NULL represents unlimited
                'monthly' => 1
            ],
        ];

        foreach ($plans as $plan) {
            PlanSubscription::updateOrCreate(['subscription_tier' => $plan['subscription_tier']], $plan);
        }
    }
}
