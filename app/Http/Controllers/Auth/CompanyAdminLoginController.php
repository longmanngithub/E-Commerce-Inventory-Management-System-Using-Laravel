<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyAdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // General login view
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Try to log in as an admin first
        if (Auth::guard('company_admin')->attempt(['admin_email' => $credentials['email'], 'password' => $credentials['password']], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // If admin login fails, try to log in as staff
        if (Auth::guard('company_staff')->attempt(['staff_email' => $credentials['email'], 'password' => $credentials['password']], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records for any user type.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('company_admin')->check()) {
            Auth::guard('company_admin')->logout();
        } elseif (Auth::guard('company_staff')->check()) {
            Auth::guard('company_staff')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
