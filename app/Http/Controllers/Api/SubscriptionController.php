<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\PlanSubscription;
use App\Models\SubscriptionOrder;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        // Sanitize the input before validation
        $request->merge([
            'card_number' => preg_replace('/[^0-9]/', '', $request->input('card_number'))
        ]);

        $validated = $request->validate([
            'plan_id' => 'required|integer|exists:plan_subscription,subscription_id',
            'card_number' => 'required|string|digits_between:15,16',
            'expiry_date' => ['required', 'string', 'regex:~^(0[1-9]|1[0-2])\s*/\s*\d{2}$~'],
            'cvv' => 'required|string|digits:3',
            'billing_address' => 'required|string|max:255'
        ]);

        // Get the authenticated user and their company from the API token
        $user = $request->user();
        $company = $user->company;

        // Use updateOrCreate to handle new subscriptions or plan changes
        $subscription = SubscriptionOrder::updateOrCreate(
            ['company_id' => $company->company_id],
            [
                'subscription_tier' => PlanSubscription::find($validated['plan_id'])->subscription_tier,
                'subscription_price' => PlanSubscription::find($validated['plan_id'])->subscription_price,
                'is_paid' => true,
                'monthly' => PlanSubscription::find($validated['plan_id'])->monthly,
                'start_date' => now(),
                'renew_date' => now()->addMonth(),
                'company_admin_id' => $user->admin_id,
            ]
        );

        return response()->json(['message' => 'Subscription successful!'], 201);
    }
}
