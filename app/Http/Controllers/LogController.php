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
        $response = $this->api($request)->get(config('services.api.url').'/logs', $request->query());
        $apiData = $response->json();

        $logs = new LengthAwarePaginator(
            $apiData['data'] ?? [],
                $apiData['meta']['total'] ?? 0,
                $apiData['meta']['per_page'] ?? 15,
            $apiData['meta']['current_page'] ?? 1,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('logs.index', compact('logs'));
    }
}
