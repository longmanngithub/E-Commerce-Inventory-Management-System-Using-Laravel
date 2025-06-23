<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    private function api(Request $request)
    {
        return Http::withToken($request->session()->get('api_token'))
            ->withHeaders(['Accept' => 'application/json']);
    }

    /**
     * Display a listing of the orders (from API).
     */
    public function index(Request $request)
    {
        $response = $this->api($request)->get(config('services.api.url').'/orders', $request->query());

        $apiData = $response->json();

        $orders = new LengthAwarePaginator(
            $apiData['data'] ?? [],
            $apiData['meta']['total'] ?? 0,
            $apiData['meta']['per_page'] ?? 15,
            $apiData['meta']['current_page'] ?? 1,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('orders.index', compact('orders'));
    }

    /**
     * Display a single order.
     */
    public function show(Request $request, $orderId)
    {
        $response = $this->api($request)->get(config('services.api.url')."/orders/{$orderId}");

        if ($response->failed()) {
            abort(404);
        }

        return view('orders.show', ['order' => $response->json('data')]);
    }

    /**
     * Cancel the order.
     */
    public function cancel(Request $request, $orderId)
    {
        $response = $this->api($request)->post(config('services.api.url')."/orders/{$orderId}/cancel");

        if ($response->failed()) {
            dd($response->json());
            return back()->with('error', $response->json('message', 'Failed to cancel order.'));
        }

        return back()->with('status', $response->json('message'));
    }

    /**
     * Export a single order's details as a CSV by calling the API.
     */
    public function exportCsv(Request $request, $orderId)
    {
        $token = $request->session()->get('api_token');
        $response = Http::withToken($token)
            ->withHeaders(['Accept' => 'text/csv'])
            ->get(config('services.api.url')."/orders/{$orderId}/export-csv");

        if ($response->failed()) {
            return back()->with('error', 'Could not export order details at this time.');
        }

        // Stream the CSV response from the API directly to the user's browser
        return new StreamedResponse(function () use ($response) {
            echo $response->body();
        }, 200, $response->headers());
    }
}
