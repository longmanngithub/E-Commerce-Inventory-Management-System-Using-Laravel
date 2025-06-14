<?php

namespace App\Http\Controllers\PlatformOwner;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CompanyController extends Controller
{
    /**
     * Display a listing of all companies on the platform by calling the API.
     */
    public function index()
    {
        // 1. Get the API token that was saved in the session during login
        $token = session('api_token');

        if (!$token) {
            // If there's no token, something is wrong, force a logout.
            Auth::guard('platform_owner')->logout();
            return redirect()->route('login')->withErrors(['owner_email' => 'Your session has expired. Please log in again.']);
        }

        // 2. Use the token to fetch the list of companies
        $companiesResponse = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->get(config('services.api.url').'/companies');

        if ($companiesResponse->failed()) {
            return "Error: Could not fetch companies from the API.";
        }

        $companies = $companiesResponse->json('data');

        // 3. Pass the company data to the view
        return view('platform-owner.companies.index', compact('companies'));
    }

    /**
     * Get a company overview
     *
     * @param $companyId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function show($companyId)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->withHeaders(['Accept' => 'application/json'])
            ->get(config('services.api.url').'/companies/' . $companyId);

        if ($response->failed()) {
            abort(404, 'Company data not found.');
        }

        $companyData = $response->json('data');

        return view('platform-owner.companies.show', compact('companyData'));
    }

    /**
     * Reactivate a company's account and subscription
     *
     * @param $companyId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function reactivate($companyId)
    {
        $token = session('api_token');
        $response = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/companies/' . $companyId . '/reactivate');

        if ($response->failed()) {
            return back()->with('error', 'Failed to reactivate company.');
        }

        return back()->with('status', $response->json('message'));
    }
}
