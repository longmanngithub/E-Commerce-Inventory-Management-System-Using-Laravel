<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CompanyAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;
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
     * Validate user data and store it in the session.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:company_admin,admin_email', 'unique:company_staff,staff_email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        // Store the validated user data in the session.
        $request->session()->put('registration_data', $validated);

        // Redirect to the next step (company form).
        return redirect()->route('register.company.show');
    }

    /**
     * Validate company data, combine with session data, and send to API
     */
    public function storeCompany(Request $request)
    {
        // Retrieve the user data we saved in the session.
        $userData = $request->session()->get('registration_data');
        if (!$userData) {
            return redirect()->route('register');
        }

        // Validate the company data from the current form.
        $companyData = $request->validate([
            'company_name' => ['required', 'string', 'max:128'],
            'company_email' => ['required', 'string', 'email', 'max:128', 'unique:company,company_email'],
            'company_address' => ['required', 'string', 'max:255'],
        ]);

        // Call the API's central register endpoint with all the data.
        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/auth/register', [
                // User data from session
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'password_confirmation' => $userData['password'],
                // Company data from form
                'company_name' => $companyData['company_name'],
                'company_email' => $companyData['company_email'],
                'company_address' => $companyData['company_address'],
            ]);

        // Handle API errors
        if ($response->status() === 422) {
            return back()->withErrors($response->json('errors'))->withInput();
        }
        if ($response->failed()) {
            return back()->with('error', 'An unexpected error occurred.');
        }

        // If API call succeeds, log the new user in
        $request->session()->forget('registration_data');
        $user = new CompanyAdmin();
        $user->forceFill($response->json('user'));
        $user->exists = true;
        Auth::guard('company_admin')->login($user);
        $request->session()->regenerate();
        $request->session()->put('api_token', $response->json('token'));

        return redirect()->route('subscription.plans');
    }

    /**
     * Display the second step of the registration form (company details).
     */
    public function showCompanyForm(Request $request)
    {
        // Security check: ensure user has completed account registration.
        if (!$request->session()->has('registration_data')) {
            return redirect()->route('register');
        }
        return view('auth.company-register');
    }
}
