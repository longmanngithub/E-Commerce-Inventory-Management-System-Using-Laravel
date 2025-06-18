<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserInvitationResource;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class InvitationController extends Controller
{
    /**
     * Verify an invitation token and return its details.
     */
    public function show($token)
    {
        $invitation = UserInvitation::where('token', $token)->first();

        if (!$invitation) {
            return response()->json(['message' => 'This invitation link is invalid or has expired.'], 404);
        }

        return new UserInvitationResource($invitation);
    }

    /**
     * Complete the invitation process by setting a password and creating the user.
     */
    public function complete(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string|exists:user_invitations,token',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $invitation = UserInvitation::where('token', $validated['token'])->firstOrFail();

        // Use a transaction to create the user and delete the invitation
        DB::transaction(function () use ($invitation, $validated) {
            $userModel = $invitation->role === 'admin' ? CompanyAdmin::class : CompanyStaff::class;
            $nameColumn = $invitation->role === 'admin' ? 'admin_name' : 'staff_name';
            $emailColumn = $invitation->role === 'admin' ? 'admin_email' : 'staff_email';
            $passwordColumn = $invitation->role === 'admin' ? 'admin_password' : 'staff_password';

            $userModel::create([
                $nameColumn => $invitation->name,
                $emailColumn => $invitation->email,
                $passwordColumn => Hash::make($validated['password']),
                'company_id' => $invitation->company_id,
            ]);

            // Delete the invitation so it cannot be used again
            $invitation->delete();
        });

        return response()->json(['message' => 'Account created successfully! You can now log in.']);
    }
}
