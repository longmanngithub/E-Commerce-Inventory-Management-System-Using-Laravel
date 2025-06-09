<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CompanyAdmin;
use Illuminate\Http\Request;
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
     * Handle the first step of registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:company_admin,admin_email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        // Create the admin user in the database
        $admin = CompanyAdmin::create([
            'admin_name' => $request->name,
            'admin_email' => $request->email,
            'admin_password' => Hash::make($request->password),
        ]);

        // Store the new admin's ID in the session to track them through the flow
        $request->session()->put('new_admin_id', $admin->admin_id);

        // Redirect to the next step
        return redirect()->route('register.company.show');
    }

    /**
     * Display the second step of the registration form (company details).
     */
    public function showCompanyForm(Request $request)
    {
        // Ensure user has completed step 1
        if (!$request->session()->has('new_admin_id')) {
            return redirect()->route('register.show');
        }
        return view('auth.company-register');
    }

    /**
     * Handle the second step of registration.
     */
    public function storeCompany(Request $request)
    {
        // Ensure user has completed step 1
        if (!$request->session()->has('new_admin_id')) {
            return redirect()->route('register.show');
        }

        $request->validate([
            'company_name' => ['required', 'string', 'max:128'],
            'company_email' => ['required', 'string', 'email', 'max:128', 'unique:company,company_email'],
            'company_address' => ['required', 'string', 'max:255'],
        ]);

        $company = Company::create([
            'company_name' => $request->company_name,
            'company_email' => $request->company_email,
            'company_address' => $request->company_address,
            'register_date' => now(),
        ]);

        // Link the company to the admin user we created in step 1
        $adminId = $request->session()->get('new_admin_id');
        $admin = CompanyAdmin::find($adminId);
        $admin->company_id = $company->company_id;
        $admin->save();

        // At this point you would redirect to the Choose Plan / Payment page.
        // For now, let's log the user in.
        Auth::guard('company_admin')->login($admin);

        // Forget the session variable
        $request->session()->forget('new_admin_id');

        return redirect()->route('dashboard');
    }
}
