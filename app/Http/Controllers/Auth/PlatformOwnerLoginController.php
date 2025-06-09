<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlatformOwnerLoginController extends Controller
{
    /**
     * Show the platform owner's login form.
     */
    public function showLoginForm()
    {
        return view('auth.owner-login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'owner_email' => 'required|email',
            'owner_password' => 'required',
        ]);

        // IMPORTANT: We specify the 'platform_owner' guard here.
        if (Auth::guard('platform_owner')->attempt(['owner_email' => $credentials['owner_email'], 'password' => $credentials['owner_password']], $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirect to the owner's dashboard
            return redirect()->intended('/owner/dashboard');
        }

        return back()->withErrors([
            'owner_email' => 'The provided credentials do not match our records.',
        ])->onlyInput('owner_email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::guard('platform_owner')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
