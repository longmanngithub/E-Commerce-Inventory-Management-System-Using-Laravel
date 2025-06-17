<?php

namespace App\Http\Controllers;

use App\Mail\UserInvitationMail;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use Illuminate\Http\Request;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class CompanyUserController extends Controller
{
    private function api(Request $request)
    {
        return Http::withToken($request->session()->get('api_token'))->withHeaders(['Accept' => 'application/json']);
    }

    /**
     * List all users by calling the API.
     */
    public function index(Request $request)
    {
        $response = $this->api($request)->get(config('services.api.url').'/users');
        $users = $response->json('data', []);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for inviting a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Send the invitation data to the API.
     */
    public function store(Request $request) // This handles the invitation
    {
        $response = $this->api($request)->post(config('services.api.url').'/users/invite', $request->all());

        if ($response->failed()) {
            return back()->withErrors($response->json('errors'))->withInput();
        }

        return redirect()->route('management.users.index')->with('status', 'User invitation sent successfully!');
    }

    /**
     * Update a staff member's permissions by calling the API.
     */
    public function updateStaff(Request $request, $userId)
    {
        $response = $this->api($request)->put(config('services.api.url')."/users/staff/{$userId}", [
            'permissions' => $request->input('permissions', []),
        ]);

        if ($response->failed()) {
            return back()->with('error', 'Failed to update user permissions.');
        }

        return redirect()->route('management.users.index')->with('status', 'User permissions updated.');
    }

    /**
     * Delete staff account
     */
    public function destroyStaff(Request $request, $userId)
    {
        $response = $this->api($request)->delete(config('services.api.url')."/users/staff/{$userId}");

        if ($response->failed()) {
            return back()->with('error', $response->json('message', 'Failed to delete user.'));
        }

        return redirect()->route('management.users.index')->with('status', 'User deleted.');
    }

    /**
     * Delete admin account
     */
    public function destroyAdmin(Request $request, $userId)
    {
        $response = $this->api($request)->delete(config('services.api.url')."/users/admin/{$userId}");

        if ($response->failed()) {
            return back()->with('error', $response->json('message', 'Failed to delete user.'));
        }

        return redirect()->route('management.users.index')->with('status', 'User deleted.');
    }
}
