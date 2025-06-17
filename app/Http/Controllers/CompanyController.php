<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Setup reusable API request helper.
     */
    private function api(Request $request)
    {
        return Http::withToken($request->session()->get('api_token'))
            ->withHeaders(['Accept' => 'application/json']);
    }

    /**
     * Show the form for editing the current user's company using the API.
     */
    public function edit(Request $request)
    {
        $response = $this->api($request)->get(config('services.api.url').'/company');

        if ($response->failed()) {
            abort(500, 'Could not fetch company data.');
        }

        return view('company.edit', ['company' => $response->json('data')]);
    }

    /**
     * Update the company's information via API.
     */
    public function update(Request $request)
    {
        $http = $this->api($request);
        $payload = $request->except(['company_image', '_token', '_method']);
        $payload['_method'] = 'PUT'; // Spoofing for file upload

        if ($request->hasFile('company_image')) {
            $http->attach('company_image', file_get_contents($request->company_image), $request->company_image->getClientOriginalName());
        }

        $response = $http->post(config('services.api.url').'/company', $payload);

        if ($response->failed()) {
            return back()->withErrors($response->json('errors'))->withInput();
        }
        return redirect()->route('management.company.edit')->with('status', 'Company profile updated!');
    }

    /**
     * Deactivate the current user's company using API.
     */
    public function deactivate(Request $request)
    {
        $response = $this->api($request)->post(config('services.api.url').'/company/deactivate', [
            'password' => $request->password, // Pass the password to the API
        ]);

        // Authorize the action (this should already be in place)
        $this->authorize('deactivate-company', auth()->user()->company);

        $company = $request->user()->company;

        // Get all admin and staff IDs belonging to this company.
        $adminIds = $company->admins()->pluck('admin_id')->all();
        $staffIds = $company->staff()->pluck('staff_id')->all();
        $allUserIds = array_merge($adminIds, $staffIds);

        // Invalidate all sessions associated with these user IDs.
        // Note: This assumes you are using the default 'file' session driver.
        $sessionPath = storage_path('framework/sessions');
        $sessionFiles = File::files($sessionPath);

        foreach ($sessionFiles as $file) {
            $sessionId = $file->getFilename();
            $sessionData = unserialize(File::get($file->getPathname()));

            // Laravel stores the logged-in user's ID in the session key.
            $sessionUserId = last(explode('_', array_key_first($sessionData)));

            if (in_array($sessionUserId, $allUserIds)) {
                // If the session belongs to a user from the deactivated company, delete it.
                Session::getHandler()->destroy($sessionId);
            }
        }

        // The API call remains the same.
        $response = $this->api($request)->post(config('services.api.url').'/company/deactivate');
        if ($response->failed()) {
            return back()->with('error', 'Failed to deactivate company via API.');
        }

        // Log out the current user and redirect to the login page.
        Auth::guard('company_admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Your company account has been deactivated.');
    }
}
