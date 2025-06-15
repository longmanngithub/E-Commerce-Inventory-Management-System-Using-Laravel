<?php

namespace App\Http\Controllers;

use App\Models\PlanSubscription;
use App\Models\SubscriptionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SubscriptionController extends Controller
{
    /**
     * Display the subscription plan options.
     */
    public function index()
    {
        // Make the API call to get the list of plans
        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->get(config('services.api.url').'/plans');

        // First, check if the API call failed for any reason.
        if ($response->failed()) {
            // If it failed, send an empty array to the view to prevent the error.
            // We also send an error message to inform the user.
            $plans = [];
            $isChangingPlan = false; // Set a default
            return view('subscriptions.plans', compact('plans', 'isChangingPlan'))
                ->with('error', 'Could not load subscription plans at this time. Please try again later.');
        }

        // If the call was successful, get the data from the 'data' key.
        // We default to an empty array just in case the 'data' key is missing.
        $plans = $response->json('data', []);

        // Determine if the logged-in user is changing their plan or signing up.
        $isChangingPlan = false;
        if (Auth::check()) {
            $company = Auth::user()->company;
            $isChangingPlan = $company && $company->subscription;
        }

        return view('subscriptions.plans', compact('plans', 'isChangingPlan'));
    }

    /**
     * Show the checkout page for a selected plan.
     */
    public function checkout($planId)
    {
        // Pass the selected plan to the checkout view
        $response = Http::get(config('services.api.url').'/plans');
        $plans = $response->json('data');
        $plan = collect($plans)->firstWhere('id', $planId);

        if (!$plan) { abort(404); }

        return view('subscriptions.checkout', compact('plan'));
    }

    /**
     * Store the new subscription for the user (simulating a successful payment).
     */
    public function storeSubscription(Request $request)
    {
        $token = $request->session()->get('api_token');

        // The API is now responsible for validation.
        $response = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/subscriptions', [
                'plan_id' => $request->plan_id,
                'card_number' => $request->card_number,
                'expiry_date' => $request->expiry_date,
                'cvv' => $request->cvv,
                'billing_address' => $request->billing_address,
            ]);

        // If the API returns validation errors, send them back to the form
        if ($response->status() === 422) {
            return back()->withErrors($response->json('errors'))->withInput();
        }
        if ($response->failed()) {
            return back()->with('error', 'An unexpected error occurred. Please try again.');
        }

        // If successful, redirect to the dashboard
        return redirect()->route('dashboard')->with('status', 'Subscription successful! Welcome aboard.');
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
