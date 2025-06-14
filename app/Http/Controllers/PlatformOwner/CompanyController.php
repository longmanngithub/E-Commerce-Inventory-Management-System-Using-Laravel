<?php

namespace App\Http\Controllers\PlatformOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of all companies on the platform by calling the API.
     */
    public function index()
    {
        // 1. Log in to the API to get a token
        $loginResponse = Http::withHeaders(['Accept' => 'application/json'])
            ->post(config('services.api.url').'/auth/login', [
                'email' => config('services.api.email'),
                'password' => config('services.api.password'),
            ]);

        // If the login fails, show an error
        if ($loginResponse->failed()) {
            return "Error: Could not authenticate with the API.";
        }

        $token = $loginResponse->json('token');

        // 2. Use the token to fetch the list of companies
        $companiesResponse = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->get(config('services.api.url').'/companies');

        // If the request fails, show an error
        if ($companiesResponse->failed()) {
            return "Error: Could not fetch companies from the API.";
        }

        $companies = $companiesResponse->json('data');

        // 3. Pass the company data to the view
        return view('platform-owner.companies.index', compact('companies'));
    }
}
