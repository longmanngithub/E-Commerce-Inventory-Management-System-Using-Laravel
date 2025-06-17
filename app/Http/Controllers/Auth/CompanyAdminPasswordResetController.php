<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserInvitationMail;
use App\Mail\SendVerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\CompanyAdmin;
use Illuminate\Support\Str;

class CompanyAdminPasswordResetController extends Controller
{
    /**
     * Display the form to request a password reset link.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send the verification code by calling the API.
     */
    public function sendVerificationCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/forgot-password', [
                'email' => $request->email,
            ]);

        if ($response->failed()) {
            return back()->withErrors(['email' => $response->json('message', 'An error occurred.')])->withInput();
        }

        return redirect()->route('password.verify.form')->with('email', $request->email);
    }

    /**
     * Show the form to enter the verification code.
     */
    public function showVerificationForm(Request $request)
    {
        if (!$request->session()->has('email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-code', ['email' => $request->session()->get('email')]);
    }

    /**
     * Verify the code by calling the API.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric|digits:6',
        ]);

        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/verify-code', [
                'email' => $request->email,
                'code' => $request->code,
            ]);

        if ($response->failed()) {
            return back()->withErrors(['code' => $response->json('message', 'Invalid code.')])->withInput();
        }

        $resetToken = $response->json('reset_token');
        return redirect()->route('password.reset', ['token' => $resetToken, 'email' => $request->email]);
    }

    /**
     * Show the form to set a new password.
     */
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }

    /**
     * Update the password by calling the API.
     */
    public function reset(Request $request)
    {
        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/reset-password', [
                'email' => $request->email,
                'token' => $request->token,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ]);

        if ($response->failed()) {
            return back()->withErrors(['email' => $response->json('message', 'An error occurred.')])->withInput();
        }

        return redirect()->route('login')->with('status', $response->json('message'));
    }
}
