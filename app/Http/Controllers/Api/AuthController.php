<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\PlatformOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);
        $email = $request->email;
        $password = $request->password;

        // Try Platform Owner
        $user = PlatformOwner::where('owner_email', $email)->first();
        if ($user && Hash::check($password, $user->owner_password)) {
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('api-token')->plainTextToken,
                'guard' => 'platform_owner'
            ]);
        }

        // Try Company Admin
        $user = CompanyAdmin::where('admin_email', $email)->first();
        if ($user && Hash::check($password, $user->admin_password)) {
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('api-token')->plainTextToken,
                'guard' => 'company_admin'
            ]);
        }

        // Try Company Staff
        $user = CompanyStaff::where('staff_email', $email)->first();
        if ($user && Hash::check($password, $user->staff_password)) {
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('api-token')->plainTextToken,
                'guard' => 'company_staff'
            ]);
        }

        return response()->json([
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function register(Request $request)
    {
        // Validate all data from the company and user forms at once
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:company_admin,admin_email', 'unique:company_staff,staff_email'],
            'password' => ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'company_name' => ['required', 'string', 'max:128'],
            'company_email' => ['required', 'string', 'email', 'max:128', 'unique:company,company_email'],
            'company_address' => ['required', 'string', 'max:255'],
        ]);

        // Use a Database Transaction for safety
        $user = DB::transaction(function () use ($validated) {
            // First, create the company
            $company = Company::create([
                'company_name' => $validated['company_name'],
                'company_email' => $validated['company_email'],
                'company_address' => $validated['company_address'],
                'register_date' => now(),
            ]);

            // Then, create the admin, mark them as the owner, and link to the new company
            return CompanyAdmin::create([
                'admin_name' => $validated['name'],
                'admin_email' => $validated['email'],
                'admin_password' => Hash::make($validated['password']),
                'company_id' => $company->company_id,
                'is_owner' => true,
            ]);
        });

        // If successful, create a new API token for the user
        $token = $user->createToken('api-token-on-register')->plainTextToken;

        // Return the new user, token, and guard in a JSON response
        return response()->json([
            'user' => $user,
            'token' => $token,
            'guard' => 'company_admin'
        ], 201); // 201 Created status
    }
}
