<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\PlatformOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        $imageColumn = match(get_class($user)) {
            CompanyAdmin::class => 'admin_image',
            CompanyStaff::class => 'staff_image',
            PlatformOwner::class => 'owner_image',
        };

        // Validate the incoming data using the dynamic column names.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique($user->getTable(), $emailColumn)->ignore($user->getKey(), $user->getKeyName())],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        // Update the database with the correct columns.
        $user->forceFill([
            $nameColumn => $validated['name'],
            $emailColumn => $validated['email'],
        ]);

        // Check if a new profile picture was uploaded
        if ($request->hasFile('profile_picture')) {
            // Read the image file content and save it to the blob column
            $user->{$imageColumn} = file_get_contents($request->file('profile_picture')->getRealPath());
        }

        $user->save();

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

    /**
     * Show profile photo
     */
    public function showPhoto($userType, $userId)
    {
        // THE FIX: Use the userType from the URL to find the user in the correct table
        $user = match ($userType) {
            'owner' => \App\Models\PlatformOwner::find($userId),
            'admin' => \App\Models\CompanyAdmin::find($userId),
            'staff' => \App\Models\CompanyStaff::find($userId),
            default => null,
        };

        if (!$user) {
            abort(404, 'User not found.');
        }

        $imageColumn = $user->getImageUrlColumn();
        $path = $user->{$imageColumn};

        if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            $file = \Illuminate\Support\Facades\Storage::disk('public')->get($path);
            $type = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($path);
            return response($file)->header('Content-Type', $type);
        }

        abort(404, 'User has no photo.');
    }

    /**
     * Update the user's profile picture.
     */
    public function updatePhoto(Request $request)
    {
        // Validate the incoming file.
        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        $user = $request->user();
        $imageColumn = $user->getImageUrlColumn(); // Get the correct column name ('owner_image', etc.)

        // Check if a file was actually uploaded.
        if ($request->hasFile('profile_picture')) {
            // Delete the old photo from storage if it exists to save space.
            if ($user->{$imageColumn}) {
                Storage::disk('public')->delete($user->{$imageColumn});
            }

            // Store the new photo in 'storage/app/public/profile-photos'
            //    and get the path to save in the database.
            $path = $request->file('profile_picture')->store('profile-photos', 'public');

            // Save the new file path to the user's image column.
            $user->{$imageColumn} = $path;
            $user->save();
        }

        return response()->json(['message' => 'Profile picture updated successfully.']);
    }
}
