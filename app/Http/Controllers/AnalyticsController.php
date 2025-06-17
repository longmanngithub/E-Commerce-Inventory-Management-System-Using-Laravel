<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    private function api(Request $request)
    {
        return Http::withToken($request->session()->get('api_token'))->withHeaders(['Accept' => 'application/json']);
    }

    /**
     * Display the analytics report by fetching all data from the API.
     */
    public function index(Request $request)
    {
        // Make one single, authenticated call to your API's analytics endpoint
        // Pass along any query params like time_range for the chart
        $response = $this->api($request)->get(config('services.api.url').'/analytics-report', $request->query());

        if ($response->failed()) {
            return view('analytics.index', ['analyticsData' => []])
                ->with('error', 'Could not load analytics data at this time.');
        }

        $analyticsData = $response->json('data');

        // Pass the single data array from the API to the view
        return view('analytics.index', compact('analyticsData'));
    }

    /**
     * Handle the export request by calling the API and streaming the response.
     */
    public function exportCsv(Request $request)
    {
        // Call the API's export endpoint
        $response = $this->api($request)
            ->get(config('services.api.url').'/analytics-report/export', $request->query());

        // If the API call fails, redirect back with an error
        if ($response->failed()) {
            return back()->with('error', 'Could not export report at this time.');
        }

        $fileName = "analytics_report_" . now()->format('Y-m-d') . ".csv";

        // Stream the CSV response from the API directly to the user's browser
        return new StreamedResponse(function () use ($response) {
            echo $response->body();
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ]);
    }
}
