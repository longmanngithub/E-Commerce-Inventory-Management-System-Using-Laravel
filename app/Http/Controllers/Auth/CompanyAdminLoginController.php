<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class CompanyAdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // General login view
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Call the API (This part is correct)
        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/auth/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

        // 2. Handle API login failure
        if ($response->failed()) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
        }

        // 3. Get the user's ID and guard from the API response
        $userData = $response->json('user');
        $guard = $response->json('guard');
        $userId = $userData['admin_id'] ?? $userData['staff_id'];
        $userModelClass = $guard === 'company_admin' ? \App\Models\CompanyAdmin::class : \App\Models\CompanyStaff::class;

        // 4. THE FIX: Fetch a FRESH, complete user object from the LOCAL database.
        $user = $userModelClass::with('company.subscription')->find($userId);

        // 5. If the user is found locally, log them into the session
        if ($user) {
            Auth::guard($guard)->login($user, $request->filled('remember'));
            $request->session()->regenerate();
            $request->session()->put('api_token', $response->json('token'));

            return redirect()->intended(route('dashboard'));
        }

        // This is a fallback error in case the database is out of sync.
        return back()->withErrors(['email' => 'An authentication error occurred. Please contact support.'])->onlyInput('email');
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
