<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CompanyAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CompanyAdminRegisterController extends Controller
{
    /**
     * Display the first step of the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.admin-register');
    }

    /**
     * Store the account registration
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeRegistration(Request $request)
    {
        // 1. Validate ALL data from both forms at once
        $validated = $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'email', 'max:255', 'unique:company_admin,admin_email'],
            'user_password' => ['required', 'string', 'confirmed', 'min:8'],
            'company_name' => ['required', 'string', 'max:128'],
            'company_email' => ['required', 'string', 'email', 'max:128', 'unique:company,company_email'],
            'company_address' => ['required', 'string', 'max:255'],
        ]);

        // 2. Use a Database Transaction for safety
        try {
            DB::transaction(function () use ($validated,  $request) {
                // First, create the company
                $company = Company::create([
                    'company_name' => $validated['company_name'],
                    'company_email' => $validated['company_email'],
                    'company_address' => $validated['company_address'],
                    'register_date' => now(),
                ]);

                // Then, create the admin and link them to the new company
                $admin = CompanyAdmin::create([
                    'admin_name' => $validated['user_name'],
                    'admin_email' => $validated['user_email'],
                    'admin_password' => Hash::make($validated['user_password']),
                    'company_id' => $company->company_id,
                    'is_owner' => true,
                ]);

                Auth::guard('company_admin')->login($admin);

                // Regenerate the session after logging in
                $request->session()->regenerate();
            });
        } catch (\Exception $e) {
            // If anything fails, redirect back with an error
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }

        // 3. If the transaction was successful, redirect to the subscription plans page
        return redirect()->route('subscription.plans');
    }

    /**
     * Display the second step of the registration form (company details).
     */
    public function showCompanyForm(Request $request)
    {
        // Validate the first step's data
        $userData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:company_admin,admin_email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        // Pass the validated user data to the company registration view
        return view('auth.company-register', compact('userData'));
    }
}
