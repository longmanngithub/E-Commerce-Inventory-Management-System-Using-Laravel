<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminCompanyResource;
use App\Models\Company;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AdminCompanyController extends Controller
{
    public function __construct(protected AuditLogService $auditLogService) {}

    /**
     * Display the current user's company information.
     */
    public function show(Request $request)
    {
        $company = $request->user()->company->load('subscription');

        return new AdminCompanyResource($company);
    }

    /**
     * Update the authenticated user's company info.
     */
    public function update(Request $request)
    {
        $company = $request->user()->company;
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:128',
            'company_email' => 'required|email|max:128',
            'company_address' => 'required|string|max:255',
            'company_website' => 'nullable|url',
            'company_telephone' => 'nullable|string',
            'company_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('company_image')) {
            if ($company->company_image) { Storage::delete($company->company_image); }
            $path = $request->file('company_image')->store('company-logos');
            $validatedData['company_image'] = $path;
        }

        $company->update($validatedData);

        $this->auditLogService->log($request, 'Updated', "Updated company information", $company);

        return new AdminCompanyResource($company);
    }

    /**
     * Deactivate the current user's company.
     */
    public function deactivate(Request $request)
    {
        // Authorize that the user can perform this action
        $user = $request->user();
        if (!$user->is_owner) {
            return response()->json(['message' => 'Only the company owner can deactivate the company.'], 403);
        }

        // First, validate that a password was submitted
        $request->validate(['password' => 'required|string']);

        // Securely compare the submitted password with the user's actual password.
        if (!Hash::check($request->password, $user->getAuthPassword())) {
            // If it doesn't match, throw the specific validation error.
            throw ValidationException::withMessages([
                'password' => 'The provided password does not match your records.',
            ]);
        }

        // --- If the password is correct, proceed with deactivation ---
        $company = $user->company;
        $company->status = 'Inactive';
        $company->save();

        // Also cancel the subscription if it exists
        if ($company->subscription) {
            $company->subscription->update(['is_paid' => false]);
        }

        return response()->json(['message' => 'Company has been deactivated.']);
    }
}
