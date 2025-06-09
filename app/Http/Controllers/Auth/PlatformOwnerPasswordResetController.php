<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PlatformOwnerPasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.owner-forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['owner_email' => 'required|email']);
        $status = Password::broker('platform_owners')->sendResetLink($request->only('owner_email'));
        return $status == Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['owner_email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.owner-reset-password')->with(
            ['token' => $token, 'owner_email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'owner_email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker('platform_owners')->reset(
            $request->only('owner_email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->owner_password = Hash::make($password);
                $user->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('owner.login')->with('status', __($status))
            : back()->withErrors(['owner_email' => __($status)]);
    }
}
