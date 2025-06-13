<?php

namespace App\Http\Controllers;

use App\Models\PlanSubscription;
use App\Models\SubscriptionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display the subscription plan options.
     */
    public function index()
    {
        // Fetch all available plans from the database
        $plans = PlanSubscription::all();

        // Get the logged-in user's company and check if a subscription already exists.
        $company = Auth::user()->company;
        $isChangingPlan = $company && $company->subscription;

        // Pass both the plans and the new boolean flag to the view
        return view('subscriptions.plans', compact('plans', 'isChangingPlan'));
    }

    /**
     * Show the checkout page for a selected plan.
     */
    public function checkout(PlanSubscription $plan)
    {
        // Pass the selected plan to the checkout view
        return view('subscriptions.checkout', compact('plan'));
    }

    /**
     * Store the new subscription for the user (simulating a successful payment).
     */
    public function storeSubscription(Request $request)
    {
        $request->merge(['card_number' => preg_replace('/[^0-9]/', '', $request->input('card_number'))]);

        // 1. Validate the plan ID and the new payment fields
        $request->validate([
            'plan_id' => 'required|integer|exists:plan_subscription,subscription_id',
            'card_number' => 'required|string|digits_between:15,16',
            'expiry_date' => ['required', 'string', 'regex:~^(0[1-9]|1[0-2])\s*/\s*\d{2}$~'],
            'cvv' => 'required|string|digits:3',
            'billing_address' => 'required|string|max:255'
        ]);

        // 2. Get the selected plan and the currently authenticated user and their company.
        $plan = PlanSubscription::findOrFail($request->input('plan_id'));
        $admin = Auth::user();
        $company = $admin->company;

        // 3. Use updateOrCreate to handle both new subscriptions and plan changes.
        // It will find a subscription for the company and update it, OR create a new one.
        SubscriptionOrder::updateOrCreate(
            ['company_id' => $company->company_id], // Find a subscription with this company_id...
            [
                // ...and update it with this data (or create it if not found).
                'subscription_tier' => $plan->subscription_tier,
                'subscription_price' => $plan->subscription_price,
                'is_paid' => true,
                'monthly' => $plan->monthly,
                'start_date' => now(),
                'renew_date' => now()->addMonth(),
                'company_admin_id' => $admin->admin_id,
            ]
        );

        // 4. Redirect the user to their dashboard with a success message.
        return redirect()->route('dashboard')->with('status', 'Subscription successful! Your plan has been updated.');
    }

    /**
     * Instantly change the subscription for an existing customer.
     */
    public function changePlan(Request $request)
    {
        $request->validate(['plan_id' => 'required|integer|exists:plan_subscription,subscription_id']);

        $plan = PlanSubscription::findOrFail($request->input('plan_id'));
        $admin = Auth::user();
        $company = Auth::user()->company;

        // Find the company's existing subscription order and update it.
        $subscription = $company->subscription;
        if ($subscription) {
            $subscription->update([
                'subscription_tier' => $plan->subscription_tier,
                'subscription_price' => $plan->subscription_price,
                // You might want to adjust billing dates here as well
                'renew_date' => now()->addMonth(),
            ]);
        } else {
            // This is a fallback in case a user without a subscription gets here.
            // We can create a new one for them.
            SubscriptionOrder::create([
                'company_id' => $company->company_id,
                'subscription_tier' => $plan->subscription_tier,
                'subscription_price' => $plan->subscription_price,
                'is_paid' => true,
                'monthly' => $plan->monthly,
                'start_date' => now(),
                'renew_date' => now()->addMonth(),
                'company_admin_id' => $admin->admin_id,
            ]);
        }

        // Redirect back to the company page with a success message
        return redirect()->route('management.company.edit')->with('status', 'Your subscription plan has been changed successfully!');
    }
}
