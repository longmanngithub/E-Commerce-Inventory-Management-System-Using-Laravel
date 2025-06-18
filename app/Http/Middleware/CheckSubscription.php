<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the logged-in user and their company.
        $user = Auth::user();
        $company = $user->company;

        // Check if the company exists and has an active subscription.
        //    We check for the 'subscription' relationship and if it's marked as 'is_paid'.
        if ($company && $company->subscription && $company->subscription->is_paid) {
            // If they have a paid subscription, let them proceed to the requested page.
            return $next($request);
        }

        // If they do not have a paid subscription, redirect them to the plans page.
        //    We add a message explaining why they were redirected.
        return redirect()->route('subscription.plans')->with('warning', 'You must choose a subscription plan to continue.');
    }
}
