<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $company = $user->company;

        // If the user is on the dashboard BUT they don't have a subscription, send them to the plans page.
        if ($request->routeIs('dashboard') && (!$company || !$company->subscription)) {
            return redirect()->route('subscription.plans');
        }

        // Otherwise, let them go wherever they were going.
        return $next($request);
    }
}
