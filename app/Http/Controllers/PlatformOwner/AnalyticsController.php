<?php

namespace App\Http\Controllers\PlatformOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnalyticsController extends Controller
{
    public function index()
    {
        $token = session('api_token');
        $response = Http::withToken($token)->withHeaders(['Accept' => 'application/json'])
            ->get(config('services.api.url').'/analytics');

        if ($response->failed()) { return "Error: Could not fetch analytics from the API."; }

        $analyticsData = $response->json('data');
        return view('platform-owner.analytics.index', compact('analyticsData'));
    }
}
