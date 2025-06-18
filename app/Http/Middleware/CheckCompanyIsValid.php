<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $company = $user->company;

        // Check 1: Is the company status active?
        if ($company && $company->status !== 'Active') {
            // Return a 403 Forbidden error with a clear message
            return response()->json(['message' => 'Your company account is inactive.'], 403);
        }

        // Check 2: Is the subscription paid?
        if ($company && (!$company->subscription || !$company->subscription->is_paid)) {
            return response()->json(['message' => 'An active subscription is required.'], 403);
        }

        return $next($request);
    }
}
