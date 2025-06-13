<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Company;
use App\Models\CompanyStaff;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Show the form for editing the current user's company.
     */
    public function edit()
    {
        $companyId = Auth::user()->company_id;
        $company = Company::findOrFail($companyId);

        // Eager load the relationships for efficiency
        $company->load('admins', 'staff', 'subscription');

        // Calculate the total users
        $totalUsers = $company->admins->count() + $company->staff->count();

        // Pass all the data to the view
        return view('company.edit', compact('company', 'totalUsers'));
    }

    /**
     * Update the company's information in the database.
     */
    public function update(Request $request)
    {
        // Get the currently logged-in admin's company
        $company = Company::findOrFail(Auth::user()->company_id);

        // Validate the incoming form data
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:128',
            'company_email' => 'required|email|max:128',
            'company_website' => 'nullable|url|max:255',
            'company_telephone' => 'nullable|string|max:32',
            'company_address' => 'nullable|string|max:255',
            'company_image' => 'nullable|image|max:2048', // e.g., max 2MB
        ]);

        // Handle the image upload if a new one is provided
        if ($request->hasFile('company_image')) {
            // Delete the old logo if it exists
            if ($company->company_image) {
                Storage::disk('public')->delete($company->company_image);
            }
            // Store the new logo and get its path
            $validatedData['company_image'] = $request->file('company_image')->store('company-logos', 'public');
        }

        // Update the company record with the validated data
        $company->update($validatedData);

        // Redirect back to the same page with a success message
        return redirect()->route('management.company.edit')->with('status', 'Company information updated successfully!');
    }


    /**
     * Deactivate the current user's company.
     */
    public function deactivate(Request $request)
    {
        $this->authorize('deactivate-company');

        // 1. Validate the user's password to confirm the action
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password:company_admin'],
        ]);

        $company = Auth::user()->company;

        // Find the company's subscription and update it
        if ($company->subscription) {
            $company->subscription->update([
                'is_paid' => false,
                'renew_date' => null, // Set the renewal date to null as it's no longer active
            ]);
        }

        // 2. Simply update the status to 'Inactive'
        $company->status = 'Inactive';
        $company->save();

        // Optional: Log out all users from that company.
        // For now, we will just log out the current admin.
        Auth::guard('company_admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your company account has been deactivated.');
    }
}
