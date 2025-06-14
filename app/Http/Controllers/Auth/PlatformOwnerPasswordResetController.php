<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendVerificationCodeMail;
use App\Models\PlatformOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\CompanyAdmin;
use Illuminate\Support\Str;

class PlatformOwnerPasswordResetController extends Controller
{
    // Display the form to request a password reset link
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send the email to the API to be processed
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function sendCode(Request $request)
    {
        $request->validate(['owner_email' => 'required|email']);

        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/forgot-password', [
                'email' => $request->owner_email,
            ]);

        if ($response->failed()) {
            return back()->withErrors(['owner_email' => $response->json('message', 'An error occurred.')])->onlyInput('owner_email');
        }

        return redirect()->route('password.verify.form')->with('email', $request->owner_email);
    }

    /**
     * Get the verification code form view
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|object
     */
    public function showVerificationForm(Request $request)
    {
        // Ensure an email is being carried over from the previous step
        if (!$request->session()->has('email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-code', ['email' => $request->session()->get('email')]);
    }

    /**
     * Verify the code with API
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function verifyCode(Request $request)
    {
        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/verify-code', [
                'email' => $request->email,
                'code' => $request->code,
            ]);

        if ($response->failed()) {
            return back()->withErrors(['code' => $response->json('message')]);
        }

        // The API returns a secure token. Pass it to the final reset form.
        $resetToken = $response->json('reset_token');
        return redirect()->route('password.reset', ['token' => $resetToken, 'email' => $request->email]);
    }

    /**
     * Get the reset password form view
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }

    /**
     * Update the password
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function updatePassword(Request $request)
    {
        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/reset-password', [
                'email' => $request->email,
                'token' => $request->token,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ]);

        if ($response->failed()) {
            return back()->withErrors(['email' => $response->json('message')]);
        }

        // If successful, redirect to login with a success message
        return redirect()->route('login')->with('status', $response->json('message'));
    }
}
