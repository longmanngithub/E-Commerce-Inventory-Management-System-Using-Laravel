<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserInvitationMail;
use App\Mail\SendVerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\CompanyAdmin;
use Illuminate\Support\Str;

class CompanyAdminPasswordResetController extends Controller
{
    // Display the form to request a password reset link
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // Send the password reset link
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Find the user (admin or staff)
        $user = \App\Models\CompanyAdmin::where('admin_email', $request->email)->first();
        $broker = 'company_admins';

        if (!$user) {
            $user = \App\Models\CompanyStaff::where('staff_email', $request->email)->first();
            $broker = 'company_staffs';
        }

        if ($user) {
            // Create a password reset token
            $token = Password::broker($broker)->createToken($user);

            // Send the email directly using our custom ForgotPasswordMail mailable
            Mail::to($user->getEmailForPasswordReset())->send(new UserInvitationMail($token, $user->getEmailForPasswordReset()));

            return back()->with('status', 'We have e-mailed your password reset link!');
        }

        return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Find if a user exists with this email (either admin or staff)
        $user = \App\Models\CompanyAdmin::where('admin_email', $request->email)->first()
            ?? \App\Models\CompanyStaff::where('staff_email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Generate a random 6-digit code
        $code = random_int(100000, 999999);

        // Store the HASHED code in the password_reset_tokens table.
        // This is more secure than storing the plain code.
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($code),
                'created_at' => now()
            ]
        );

        // Send the plain 6-digit code to the user's email
        Mail::to($request->email)->send(new SendVerificationCodeMail($code));

        // Redirect to the verification code entry form, passing the email along
        return redirect()->route('password.verify.form')->with('email', $request->email);
    }

    /**
     * Display the password reset form.
     * The $token is passed automatically from the route parameter.
     */
    public function showResetForm(Request $request)
    {
        // Check if the secure token exists in the session.
        if (!$request->session()->has('password_reset_token')) {
            return redirect()->route('admin.password.request'); // Corrected route name
        }

        return view('auth.reset-password')->with([
            'token' => $request->session()->get('password_reset_token'), // Get token from session
            'email' => $request->session()->get('password_reset_email'), // Get email from session
        ]);
    }

    // Display the verification code form
    public function showVerificationForm()
    {
        // Make sure the email from the previous step is passed to the view
        if (!session('email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-code', ['email' => session('email')]);
    }

    // Verify code function
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric|digits:6',
        ]);

        $tokenRecord = \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($tokenRecord && \Illuminate\Support\Facades\Hash::check($request->code, $tokenRecord->token)) {
            // The 6-digit code is correct.
            // Now, we create a new secure token for the final step.
            $secureResetToken = \Illuminate\Support\Str::random(60);

            // Store this new secure token in the session.
            $request->session()->put('password_reset_token', $secureResetToken);
            $request->session()->put('password_reset_email', $request->email);

            // Delete the 6-digit code token so it can't be used again.
            \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Redirect to the final reset password form
            return redirect()->route('password.reset'); // We no longer need to pass the token in the URL
        }

        return back()->withErrors(['code' => 'The verification code is invalid or has expired.']);
    }

    // Reset the user's password
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Check if the token from the form matches the one we stored in the session.
        if ($request->token !== $request->session()->get('password_reset_token')) {
            return back()->withErrors(['email' => 'Invalid session. Please try again.']);
        }

        // Find the user (admin or staff)
        $user = \App\Models\CompanyAdmin::where('admin_email', $request->email)->first()
            ?? \App\Models\CompanyStaff::where('staff_email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Update the password
        $passwordField = $user instanceof \App\Models\CompanyAdmin ? 'admin_password' : 'staff_password';
        $user->{$passwordField} = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        // Forget the session tokens
        $request->session()->forget(['password_reset_token', 'password_reset_email']);

        return redirect()->route('login')->with('status', 'Your password has been successfully reset.');
    }
}
