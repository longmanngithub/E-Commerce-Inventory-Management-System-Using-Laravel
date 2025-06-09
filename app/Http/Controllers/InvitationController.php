<?php

namespace App\Http\Controllers;

use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InvitationController extends Controller
{
    public function accept($token)
    {
        $invitation = UserInvitation::where('token', $token)->firstOrFail();
        return view('auth.set-password', ['invitation' => $invitation]);
    }

    public function storePassword(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $invitation = UserInvitation::where('token', $data['token'])->firstOrFail();

        // Determine which model to use based on the role stored in the invitation
        $model = $invitation->role === 'admin' ? CompanyAdmin::class : CompanyStaff::class;
        $nameField = $invitation->role === 'admin' ? 'admin_name' : 'staff_name';
        $emailField = $invitation->role === 'admin' ? 'admin_email' : 'staff_email';
        $passwordField = $invitation->role === 'admin' ? 'admin_password' : 'staff_password';

        $user = $model::create([
            $nameField => $data['name'], // You may need to adjust how the name is set
            $emailField => $invitation->email,
            $passwordField => Hash::make($data['password']),
            'company_id' => $invitation->company_id,
        ]);

        // Log the new user in
        Auth::guard('company_' . $invitation->role)->login($user);

        // Delete the invitation so it can't be used again
        $invitation->delete();

        return redirect()->route('dashboard')->with('status', 'Your account has been activated!');
    }
}
