<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $token = $request->session()->get('api_token');
        $response = Http::withToken($token)->withHeaders(['Accept' => 'application/json'])->get(config('services.api.url').'/user');

        if ($response->failed()) { return 'Could not fetch user profile from API.'; }

        return view('profile.edit', ['user' => $response->json()]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $token = $request->session()->get('api_token');

        $response = Http::withToken($token)->withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/user/profile-information', [
                'name' => $request->name,
                'email' => $request->email,
            ]);

        if ($response->failed()) {
            dd($response->json());
        }

        return Redirect::route('owner.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update password request
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $token = $request->session()->get('api_token');

        $response = Http::withToken($token)->withHeaders(['Accept' => 'application/json'])
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
}
