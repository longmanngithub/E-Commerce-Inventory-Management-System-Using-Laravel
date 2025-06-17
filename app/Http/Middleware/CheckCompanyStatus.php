<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the currently logged-in user (works for any of our guards)
        $user = Auth::user();

        // Check if the user and their company's status is 'Active'
        if ($user && $user->company && $user->company->status !== 'Active') {
            // If the company is NOT active, log the user out
            Auth::guard($user->guard_name)->logout(); // 'guard_name' assumes you add it to your models
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect to login with an error message
            return redirect('/login')->with('error', 'This company account has been deactivated.');
        }

        // If the company is active, proceed as normal
        return $next($request);
    }
}
