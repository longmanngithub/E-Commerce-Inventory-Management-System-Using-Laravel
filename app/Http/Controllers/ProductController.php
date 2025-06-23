<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    private function api(Request $request) {
        return Http::withToken($request->session()->get('api_token'))->withHeaders(['Accept' => 'application/json']);
    }

    public function index(Request $request)
    {
        $response = $this->api($request)->get(config('services.api.url').'/products', $request->query());

        $apiData = $response->json();

        $products = new LengthAwarePaginator(
            $apiData['data'] ?? [],
                $apiData['meta']['total'] ?? 0,
                $apiData['meta']['per_page'] ?? 15,
                $apiData['meta']['current_page'] ?? 1,
            ['path' => $request->url(), 'query' => $request->query()]);

        $categoriesResponse = $this->api($request)->get(config('services.api.url').'/categories');
        return view('products.index', ['products' => $products, 'categories' => $categoriesResponse->json('data', [])]);
    }

    /**
     * Store the product in database
     */
    public function store(Request $request)
    {
        $http = $this->api($request);

        if ($request->hasFile('product_image')) {
            $http->attach('product_image', file_get_contents($request->product_image), $request->product_image->getClientOriginalName());
        }

        $response = $http->post(config('services.api.url').'/products', $request->except('product_image'));

        if ($response->failed()) {
            return back()->withErrors($response->json('errors'))->with('error', $response->json('message'))->withInput();
        }

        return redirect()->route('products.index')->with('status', 'Product created successfully!');
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(Request $request)
    {
        $categoriesResponse = $this->api($request)->get(config('services.api.url').'/categories');

        return view('products.create', ['categories' => $categoriesResponse->json('data', [])]);
    }

    /**
     * Show the form for editing the specified product by fetching data from the API.
     */
    public function edit(Request $request, $productId)
    {
        // Make an API call to the 'show' endpoint to get the product's data
        $productResponse = $this->api($request)->get(config('services.api.url')."/products/{$productId}");

        // We also need the list of all categories for the dropdown
        $categoriesResponse = $this->api($request)->get(config('services.api.url').'/categories');

        if ($productResponse->failed()) {
            abort(404, 'Product not found.');
        }

        $backUrl = route('products.index') . '?' . http_build_query($request->query());

        return view('products.edit', [
            'product' => $productResponse->json('data'),
            'categories' => $categoriesResponse->json('data', []),
            'backUrl' => $backUrl,
        ]);
    }

    /**
     * Update the specified product by sending the data to the API.
     */
    public function update(Request $request, $productId)
    {
        $http = $this->api($request);
        $payload = $request->except(['product_image', '_token', '_method']);

        if ($request->hasFile('product_image')) {
            $http->attach('product_image', file_get_contents($request->product_image), $request->product_image->getClientOriginalName());
        }

        $response = $http->post(config('services.api.url') . "/products/{$productId}", array_merge($payload, ['_method' => 'PUT']));

        if ($response->failed()) {
            return back()->withErrors($response->json('errors'))->with('error', $response->json('message'))->withInput();
        }

        return back()->with('status', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($productId)
    {
        $this->api(request())->delete(config('services.api.url').'/products/' . $productId);

        return back()->with('status', 'Product deleted successfully.');
    }

    /**
     * Display the specified product and its purchase history.
     */
    public function show(Request $request, $productId)
    {
        // API Call 1: Get the main product data for the Overview tab
        $productResponse = $this->api($request)->get(config('services.api.url')."/products/{$productId}");
        if ($productResponse->failed()) {
            abort(404);
        }
        $productData = $productResponse->json('data');

        // API Call 2: Get the paginated purchase history for the Purchases tab
        // We pass along any sort parameters from the user's request.
        $purchasesResponse = $this->api($request)->get(config('services.api.url')."/products/{$productId}/purchases", $request->query());
        $purchaseApiData = $purchasesResponse->json();

        // Manually create the Paginator object for the view
        $purchases = new LengthAwarePaginator(
            $purchaseApiData['data'] ?? [],
            $purchaseApiData['meta']['total'] ?? 0,
            $purchaseApiData['meta']['per_page'] ?? 10,
            $purchaseApiData['meta']['current_page'] ?? 1,
            ['path' => $request->url(), 'query' => $request->query()]
        );


        return view('products.show', [
            'product' => $productData,
            'purchases' => $purchases,
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        // We can still authorize on the front-app side as a first line of defense
        $this->authorize('bulk-delete-products', Product::class);

        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:product,product_id',
        ]);

        // Get the token and send the array of IDs to the API
        $response = $this->api($request)
            ->post(config('services.api.url').'/products/bulk-delete', [
                'product_ids' => $request->product_ids,
            ]);

        if ($response->failed()) {
            return redirect()->route('products.index')->with('error', $response->json('message', 'An error occurred while deleting products.'));
        }

        return back()->with('status', 'Selected products have been deleted successfully!');
    }

    /**
     * Set status for each product
     */
    public function toggleStatus($productId)
    {
        $this->api(request())->patch(config('services.api.url').'/products/' . $productId . '/toggle-status');

        return back()->with('status', 'Product status updated.');
    }
}
