<?php

namespace App\Http\Controllers;

use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class InvitationController extends Controller
{
    /**
     * Show the set password form by verifying the token with the API.
     */
    public function accept(Request $request, $token)
    {
        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->get(config('services.api.url')."/invitations/{$token}");

        if ($response->failed()) {
            // Redirect to a page with an "Invalid Link" error
            return redirect()->route('login')->with('error', 'This invitation link is invalid or has expired.');
        }

        $invitationData = $response->json('data');
        $request->session()->put('invitation_data', $invitationData);

        return view('auth.set-password', compact('invitationData'));
    }

    /**
     * Store the new user's password by calling the API.
     */
    public function storePassword(Request $request)
    {
        $invitationData = $request->session()->get('invitation_data');
        if (!$invitationData) {
            return redirect()->route('login')->with('error', 'Your session has expired. Please use the invitation link again.');
        }

        // Tell the API to create the user
        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/invitations/complete', [
                'token' => $invitationData['token'],
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ]);

        if ($response->failed()) {
            return back()->withErrors($response->json('errors'))->withInput();
        }

        // Now that the user exists, log them in via the API
        $loginResponse = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/auth/login', [
                'email' => $invitationData['email'],
                'password' => $request->password,
            ]);

        if ($loginResponse->failed()) {
            return redirect()->route('login')->with('status', 'Account created! Please log in.');
        }

        // Get the user data and guard from the successful login response
        $userData = $loginResponse->json('user');
        $guard = $loginResponse->json('guard');

        // Get the user's ID from the correct key based on their role, as defined in your ERD
        $userId = $userData['admin_id'] ?? $userData['staff_id'] ?? null;

        if (!$userId) {
            return back()->with('error', 'Could not identify user from API response.');
        }

        // Determine the correct Model class
        $userModelClass = $guard === 'company_admin' ? \App\Models\CompanyAdmin::class : \App\Models\CompanyStaff::class;

        // Fetch a FRESH user from the local DB using the correct ID
        $user = $userModelClass::find($userId);

        if ($user) {
            Auth::guard($guard)->login($user, true); // Log in the new user
            $request->session()->regenerate();
            $request->session()->put('api_token', $loginResponse->json('token'));
            $request->session()->forget('invitation_data');

            // Redirect the NEWLY LOGGED-IN user to their dashboard
            return redirect()->intended(route('dashboard'))->with('status', 'Welcome! Your account has been activated.');
        }

        return redirect()->route('login')->with('error', 'Login after registration failed.');
    }
}
