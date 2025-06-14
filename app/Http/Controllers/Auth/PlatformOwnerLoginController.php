<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PlatformOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class PlatformOwnerLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // We can reuse Breeze's login view
    }

    public function login(Request $request)
    {
        // 1. Validate the form input from the browser
        $request->validate([
            'owner_email' => 'required|email',
            'owner_password' => 'required',
        ]);

        // 2. Make an API call to your api-app to verify credentials
        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/auth/login', [
                'email' => $request->owner_email,
                'password' => $request->owner_password,
            ]);

        // 3. If the API login fails, throw a validation error
        if ($response->failed()) {
            throw ValidationException::withMessages([
                'owner_email' => 'The provided credentials do not match our records.',
            ]);
        }

        // 4. If API login succeeds, get the user from the local database
        $owner = PlatformOwner::where('owner_email', $request->owner_email)->first();
        if ($owner) {
            // 5. Log the user into the back-app's web session
            Auth::guard('platform_owner')->login($owner, $request->filled('remember'));

            // 6. Save the API token into the session for future use
            $request->session()->regenerate();
            $request->session()->put('api_token', $response->json('token'));

            return redirect()->intended(route('owner.dashboard'));
        }

        return back()->withErrors(['owner_email' => 'An unknown error occurred.'])->onlyInput('owner_email');
    }

    public function logout(Request $request)
    {
        Auth::guard('platform_owner')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
