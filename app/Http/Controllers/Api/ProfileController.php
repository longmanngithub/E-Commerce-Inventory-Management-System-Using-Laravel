<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\PlatformOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Return the currently authenticated user's data.
     */
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Update the user's name and email.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Dynamically determine the correct column names based on the user's model type.
        // This uses the ERD to know the column names for each table.
        $nameColumn = match(get_class($user)) {
            CompanyAdmin::class => 'admin_name',
            CompanyStaff::class => 'staff_name',
            PlatformOwner::class => 'owner_name',
        };
        $emailColumn = match(get_class($user)) {
            CompanyAdmin::class => 'admin_email',
            CompanyStaff::class => 'staff_email',
            PlatformOwner::class => 'owner_email',
        };

        // Validate the incoming data using the dynamic column names.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique($user->getTable(), $emailColumn)->ignore($user->getKey(), $user->getKeyName())],
        ]);

        // Update the database with the correct columns.
        $user->forceFill([
            $nameColumn => $validated['name'],
            $emailColumn => $validated['email'],
        ])->save();

        return response()->json($user);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();
        $passwordColumn = $user->getAuthPasswordName();

        // validate the fields exist and the new password is confirmed
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // --- Manually check the current password ---
        // Securely compare the submitted 'current_password' with the user's actual password from the database.
        if (!Hash::check($validated['current_password'], $user->getAuthPassword())) {
            // If it doesn't match, throw a validation error.
            throw ValidationException::withMessages([
                'current_password' => 'The provided password does not match your current password.',
            ]);
        }

        // If the check passes, update the password in the database
        $user->forceFill([
            $passwordColumn => Hash::make($validated['password']),
        ])->save();

        return response()->json(['message' => 'Password updated successfully.']);
    }

    /**
     * Delete the authenticated user's account.
     */
    public function destroy(Request $request)
    {
        // Get the authenticated user from the token
        $user = $request->user();

        // Validate that a password was submitted
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'string'],
        ]);

        // --- Manually check the password ---
        // Securely compare the submitted password with the user's actual password.
        if (!Hash::check($request->password, $user->getAuthPassword())) {
            // If it doesn't match, throw the specific validation error that the view expects.
            throw ValidationException::withMessages([
                'password' => 'The provided password does not match your current password.',
            ])->errorBag('userDeletion');
        }

        // --- Your existing business logic checks ---
        if ($user instanceof \App\Models\CompanyAdmin) {
            if ($user->is_owner) {
                return response()->json(['message' => 'The primary company owner account cannot be deleted.'], 422);
            }
            $adminCount = \App\Models\CompanyAdmin::where('company_id', $user->company_id)->count();
            if ($adminCount <= 1) {
                return response()->json(['message' => 'You cannot delete the last admin account.'], 422);
            }
        }

        // If all checks pass, delete the user's tokens and the user record
        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Account deleted successfully.']);
    }
}
