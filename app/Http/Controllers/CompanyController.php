<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
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

        return view('management.company.edit', ['company' => $response->json('data')]);
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
        return redirect()->route('admin.management.company.edit')->with('status', 'Company profile updated!');
    }

    /**
     * Deactivate the current user's company using API.
     */
    public function deactivate(Request $request)
    {
        $response = $this->api($request)->post(config('services.api.url').'/company/deactivate');
        if ($response->failed()) { return back()->with('error', 'Failed to deactivate company.'); }

        // Deactivation was successful, now log out all users from the front-app
        Auth::guard('company_admin')->logout();
        // You might also want to invalidate staff sessions if you store them differently
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Your company account has been deactivated.');
    }
}
