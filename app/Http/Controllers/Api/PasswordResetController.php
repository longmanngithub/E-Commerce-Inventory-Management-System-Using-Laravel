<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendVerificationCodeMail;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\PlatformOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Handle an incoming request to send a password reset code.
     */
    public function sendCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        // --- NEW LOGIC: Find the user in any of the three tables ---
        $user = PlatformOwner::where('owner_email', $email)->first()
            ?? CompanyAdmin::where('admin_email', $email)->first()
            ?? CompanyStaff::where('staff_email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'We can\'t find a user with that email address.'], 422);
        }

        // Get the correct email from the found user object
        $userEmail = $user->owner_email ?? $user->admin_email ?? $user->staff_email;

        $code = random_int(100000, 999999);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $userEmail],
            ['token' => Hash::make($code), 'created_at' => now()]
        );

        Mail::to($userEmail)->send(new SendVerificationCodeMail($code));

        return response()->json(['message' => 'A verification code has been sent to your email address.']);
    }

    /**
     * Check the verification with hashed code
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric|digits:6',
        ]);

        $tokenRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        // Check if a token exists and if the submitted code matches the hashed code
        if ($tokenRecord && Hash::check($request->code, $tokenRecord->token)) {
            // The code is correct. We will now delete the 6-digit code and issue
            // a new, single-use, secure token for the final password reset step.
            $secureToken = Str::random(60);
            DB::table('password_reset_tokens')->where('email', $request->email)->update([
                'token' => Hash::make($secureToken),
            ]);

            // Return the secure token to the back-app
            return response()->json(['reset_token' => $secureToken]);
        }

        return response()->json(['message' => 'The verification code is invalid or has expired.'], 422);
    }

    /**
     * Update the password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $tokenRecord = DB::table('password_reset_tokens')->where('email', $validated['email'])->first();

        // Verify the secure token
        if (!$tokenRecord || !Hash::check($validated['token'], $tokenRecord->token)) {
            return response()->json(['message' => 'This password reset token is invalid.'], 422);
        }

        // Find the user and update their password
        $email = $validated['email'];
        $user = PlatformOwner::where('owner_email', $email)->first()
            ?? CompanyAdmin::where('admin_email', $email)->first()
            ?? CompanyStaff::where('staff_email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Determine the correct password column to update using the method from the model
        $passwordColumn = $user->getAuthPasswordName();

        // Update the user's password in the correct column
        $user->{$passwordColumn} = Hash::make($validated['password']);
        $user->save();

        // Delete the token so it cannot be used again
        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        return response()->json(['message' => 'Password has been successfully reset.']);
    }
}
