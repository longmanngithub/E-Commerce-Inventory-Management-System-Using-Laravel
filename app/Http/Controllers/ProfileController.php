<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Auth::user() automatically gets the currently authenticated user,
        // regardless of which guard they used (owner, admin, or staff).
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Dynamically determine the correct table and email column for validation
        $emailColumn = 'email'; // default
        if ($user instanceof \App\Models\CompanyAdmin) { $emailColumn = 'admin_email'; }
        if ($user instanceof \App\Models\CompanyStaff) { $emailColumn = 'staff_email'; }
        if ($user instanceof \App\Models\PlatformOwner) { $emailColumn = 'owner_email'; }

        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique($user->getTable(), $emailColumn)->ignore($user->id, $user->getKeyName())],
        ]);

        // Dynamically determine the correct name and email fields to update
        if ($user instanceof \App\Models\CompanyAdmin) {
            $user->admin_name = $validatedData['name'];
            $user->admin_email = $validatedData['email'];
        } elseif ($user instanceof \App\Models\CompanyStaff) {
            $user->staff_name = $validatedData['name'];
            $user->staff_email = $validatedData['email'];
        } elseif ($user instanceof \App\Models\PlatformOwner) {
            $user->owner_name = $validatedData['name'];
            $user->owner_email = $validatedData['email'];
        }

        $user->save();

        return redirect()->route(Auth::guard('owner')->check() ? 'owner.profile.edit' : 'admin.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        // Get the currently authenticated user
        $user = $request->user();
        // Determine the correct password column name
        $passwordColumn = 'password'; // default, will be overridden
        if ($user instanceof \App\Models\CompanyAdmin) { $passwordColumn = 'admin_password'; }
        if ($user instanceof \App\Models\CompanyStaff) { $passwordColumn = 'staff_password'; }
        if ($user instanceof \App\Models\PlatformOwner) { $passwordColumn = 'owner_password'; }

        // Validate the new password
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'string', 'current_password:'. $user->getAuthGuard()],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Update the password in the database
        $user->{$passwordColumn} = Hash::make($validated['password']);
        $user->save();

        return back()->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 1. Validate the user entered their correct password
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password:'. $request->user()->getAuthGuard()],
        ]);

        $user = $request->user();

        if ($user->is_owner) {
            abort(403, 'The primary company owner account cannot be deleted.');
        }

        // 2. --- CRITICAL BUSINESS LOGIC ---
        // Check if the user is a Company Admin
        if ($user instanceof \App\Models\CompanyAdmin) {
            // Check if they are the LAST admin in their company
            $adminCount = \App\Models\CompanyAdmin::where('company_id', $user->company_id)->count();

            if ($adminCount <= 1) {
                // If they are the last admin, prevent deletion and send an error back
                return back()->withErrors(['password' => 'You cannot delete the last admin account. Please deactivate the company instead or promote another user to admin.'])->with('error', 'Deletion failed.');
            }
        }

        // 3. If checks pass, proceed with deletion
        Auth::logout(); // Log the user out first

        $user->delete(); // Delete the user record

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been deleted.');
    }
}
