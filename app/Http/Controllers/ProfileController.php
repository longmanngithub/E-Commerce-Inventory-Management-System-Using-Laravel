<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form by fetching data from the API.
     */
    public function edit(Request $request): View
    {
        $token = $request->session()->get('api_token');
        $response = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->get(config('services.api.url').'/user');

        if ($response->failed()) {
            // Handle error if API cannot be reached or token is invalid
            abort(500, 'Could not fetch user profile from API.');
        }

        return view('profile.edit', [
            'user' => $response->json(), // Pass the user data array from the API to the view
        ]);
    }

    /**
     * Update the user's profile information by calling the API.
     */
    public function update(Request $request)
    {
        $token = $request->session()->get('api_token');

        $response = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/user/profile-information', [
                'name' => $request->name,
                'email' => $request->email,
            ]);

        // If the API returns validation errors, send them back to the form
        if ($response->status() === 422) {
            return back()->withErrors($response->json('errors'))->withInput();
        }

        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's password by calling the API.
     */
    public function updatePassword(Request $request)
    {
        $token = $request->session()->get('api_token');

        $response = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->put(config('services.api.url').'/user/password', [
                'current_password' => $request->current_password,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ]);

        if ($response->failed()) {
            return back()->withErrors($response->json('errors'), 'updatePassword');
        }

        return back()->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate that a password was submitted (the API will check if it's correct)
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'string'],
        ]);

        // Get the API token from the session
        $token = $request->session()->get('api_token');

        // Make an authenticated DELETE request to the API, passing the password
        $response = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->delete(config('services.api.url').'/user', [
                'password' => $request->password,
            ]);

        // If the API returns a validation error (e.g., wrong password, last admin), show it
        if ($response->status() === 422) {
            return back()->withErrors(['password' => $response->json('message')], 'userDeletion');
        }
        // Handle other potential API errors
        if ($response->failed()) {
            return back()->withErrors(['password' => 'An unexpected error occurred. Please try again.'], 'userDeletion');
        }

        // If the API call was successful, log the user out and redirect
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been successfully deleted.');
    }

    /**
     * Update the user's profile photo by calling the API.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate(['profile_picture' => 'required|image']);
        $token = $request->session()->get('api_token');

        $response = Http::withToken($token)
            ->attach('profile_picture', file_get_contents($request->file('profile_picture')), $request->file('profile_picture')->getClientOriginalName())
            ->post(config('services.api.url').'/user/photo');

        if ($response->failed()) {
            return back()->withErrors($response->json('errors'));
        }

        return redirect()->route('admin.profile.edit')->with('status', 'photo-updated');
    }
}
