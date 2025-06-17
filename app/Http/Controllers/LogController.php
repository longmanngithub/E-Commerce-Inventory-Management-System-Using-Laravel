<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LogController extends Controller
{
    /**
     * Helper to call internal API with auth
     */
    private function api(Request $request)
    {
        return Http::withToken($request->session()->get('api_token'))
            ->withHeaders(['Accept' => 'application/json']);
    }

    /**
     * Index function calling internal API and returning view
     */
    public function index(Request $request)
    {
        $url = config('services.api.url') . '/logs';

        // Pass along all filters from request (search, sort_by, actions, etc.)
        $response = $this->api($request)->get($url, $request->query());

        // Check for failed response
        if (!$response->successful()) {
            abort($response->status(), 'Failed to fetch audit logs from API.');
        }

        $payload = $response->json();

        $logs = new LengthAwarePaginator(
            $payload['data'] ?? [],
            $payload['meta']['total'] ?? 0,
            $payload['meta']['per_page'] ?? 10,
            $payload['meta']['current_page'] ?? 1,
            [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('logs.index', ['logs' => $logs]);
    }
}
