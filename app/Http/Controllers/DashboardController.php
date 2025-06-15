<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get the API token that was stored in the session during login
        $token = $request->session()->get('api_token');

        // Make one single, authenticated call to your API's dashboard endpoint
        $response = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->get(config('services.api.url').'/dashboard');

        // Handle a potential API failure
        if ($response->failed()) {
            // Return a view with empty data and an error message
            return view('dashboard', ['dashboardData' => []])
                ->with('error', 'Could not load dashboard data at this time.');
        }

        // Get the data from the API response
        $dashboardData = $response->json('data');

        // Pass the single data array to the view
        return view('dashboard', compact('dashboardData'));
    }
}
