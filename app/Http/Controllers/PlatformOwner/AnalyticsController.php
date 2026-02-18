<?php

namespace App\Http\Controllers\PlatformOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnalyticsController extends Controller
{
    public function index()
    {
        //take the api token that store in user after login
        $token = session('api_token');
        //use the token to request to api to take analytics data from endpoint
        $response = Http::withToken($token)->withHeaders
        //if accept, it takes the data from api responce
        (['Accept' => 'application/json'])
            ->get(config('services.api.url').'/analytics');

        if ($response->failed()) { return "Error: Could not fetch analytics from the API."; }

        //displays a webpage, showing the data to user
        $analyticsData = $response->json('data');
        return view('platform-owner.analytics.index', compact('analyticsData'));
    }
}
