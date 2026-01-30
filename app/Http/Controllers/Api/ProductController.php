<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductEditResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StockResource;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use App\Notifications\LowStockWarning;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(protected AuditLogService $auditLogService) {}

    /**
     * Display a listing of the products with filtering, searching, and sorting.
     */
    public function index(Request $request)
    {
        $query = Product::where('company_id', $request->user()->company_id);

        if ($request->filled('search')) {
            $query->where(fn($q) => $q->where('product_name', 'LIKE', "%{$request->search}%")->orWhere('product_SKU', 'LIKE', "%{$request->search}%"));
        }
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }
        if ($request->filled('in_stock_only')) {
            $query->whereHas('stocks', fn($q) => $q->where('stock_quantity', '>', 0));
        }
        if ($request->input('sort_by') === 'price_asc') {
            $query->orderBy('product_price', 'asc');
        } elseif ($request->input('sort_by') === 'price_desc') {
            $query->orderBy('product_price', 'desc');
        } else {
            $query->latest('product_id');
        }

        $products = $query->with(['category', 'stocks'])->paginate(10)->withQueryString();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $company = $user->company()->with('subscription.plan')->first();

        if (optional(optional($company->subscription)->plan)->product_limit !== null) {
            $limit = $company->subscription->plan->product_limit;

            if (Product::where('company_id', $company->company_id)->where('status', 'Active')->count() >= $limit) {
                return response()->json(['message' => "You have reached your plan's limit of {$limit} active products."], 422);
            }
        }

        $validatedData = $request->validate([
            'product_name' => ['required', 'string', 'max:128'],
            'product_SKU' => ['required', 'string', Rule::unique('product', 'product_SKU')->where('company_id', $company->company_id)],
            'category_id' => ['required', 'integer', 'exists:category,category_id'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'product_price' => ['required', 'numeric', 'min:0'],
            'purchase_date' => ['required', 'date', 'before_or_equal:today'],
            'product_expiry_date' => ['nullable', 'date'],
            'reorder_point' => ['required', 'integer', 'min:0'],
            'product_desc' => ['nullable', 'string'],
            'product_image' => ['nullable', 'image', 'max:2048']
        ]);

        $product = DB::transaction(function () use ($validatedData, $user, $request) {
            $imagePath = $request->hasFile('product_image') ? $request->file('product_image')->store('product-images') : null;
            $product = Product::create(['company_id' => $user->company_id, 'status' => 'Active', 'product_image' => $imagePath] + $validatedData);

            Stock::create(['product_id' => $product->product_id,
                           'stock_quantity' => $validatedData['stock_quantity'],
                           'purchase_price' => $validatedData['purchase_price'],
                           'stock_purchase_date' => $validatedData['purchase_date'],
                           'company_id' => $user->company_id]);

            return $product;
        });

        $this->auditLogService->log($request, 'Created', "Created product '{$product->product_name}'", $product);

        return new ProductResource($product);
    }

    /**
     * Show the data for editing the specified product.
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product); // Use the 'update' permission for editing

        return new ProductEditResource($product->load('stocks'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $this->authorize('view', $product);

        // It now only needs to return the main product details
        return new ProductDetailResource($product->load('category', 'stocks'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        // Authorize the action first
        $this->authorize('update', $product);

        // Validate all possible incoming data
        $validatedData = $request->validate([
            'reorder_point' => 'sometimes|required|integer|min:0',
            'product_desc' => 'nullable|string',
            'stock_quantity' => 'nullable|integer|min:0', // For updating the latest stock record
            'product_image' => 'nullable|image|max:2048|sometimes',
        ]);

        // Update the fields that belong to the main Product model
        $product->update([
            'reorder_point' => $validatedData['reorder_point'],
            'product_desc' => $validatedData['product_desc'],
        ]);

        // If a stock quantity was provided, update the latest stock record
        if ($request->filled('stock_quantity')) {
            $latestStock = $product->stocks()->latest('stock_purchase_date')->first();
            if ($latestStock) {
                $latestStock->update(['stock_quantity' => $validatedData['stock_quantity']]);
            }
        }

        // Handle the image upload if a new one was provided
        if ($request->hasFile('product_image')) {
            if ($product->product_image) {
                Storage::delete($product->product_image);
            }
            $imagePath = $request->file('product_image')->store('product-images');
            $product->update(['product_image' => $imagePath]);
        }

        // --- AUTOMATIC NOTIFICATION LOGIC ---
        // After all updates, recalculate the stock and check if a notification is needed
        $currentStock = $product->stocks()->sum('stock_quantity');
        if ($currentStock <= $product->reorder_point && $currentStock > 0) {
            // Find the company owner and notify them
            $companyOwner = $product->company->admins()->where('is_owner', true)->first();
            if ($companyOwner) {
                $companyOwner->notify(new \App\Notifications\LowStockWarning($product));
            }
        }

        // Log the update action
        $this->auditLogService->log($request, 'Updated', "Updated product '{$product->product_name}'", $product);

        // Return the freshly updated resource. We use fresh() to get all the latest data.
        return new ProductResource($product->fresh()->load('stocks', 'category'));
    }

    /**
     * Soft delete the specified product.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        $this->auditLogService->log(request(), 'Deleted', "Deleted product '{$product->product_name}'", $product);

        return response()->json('message', 'Product deleted successfully');
    }

    /**
     * Soft delete multiple products at once.
     */
    public function bulkDestroy(Request $request)
    {
        // Authorize that the user is allowed to perform a delete action in general.
        // This uses the ProductPolicy we created.
        $this->authorize('bulk-delete-products');

        // Validate that we received an array of product IDs.
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:product,product_id',
        ]);

        // Find all products that match the IDs AND belong to the user's company.
        // This is a crucial security check to prevent deleting another company's products.
        $productsToDelete = Product::whereIn('product_id', $validated['product_ids'])
            ->where('company_id', $request->user()->company_id)
            ->get();

        if ($productsToDelete->isEmpty()) {
            return response()->json(['message' => 'No valid products found for deletion.'], 404);
        }

        $deletedProductNames = $productsToDelete->pluck('product_name')->implode(', ');

        // Use a transaction for safety. If any deletion fails, all are rolled back.
        DB::transaction(function () use ($productsToDelete) {
            foreach ($productsToDelete as $product) {
                // Delete the image from storage if it exists.
//                if ($product->product_image) {
//                    Storage::disk('public')->delete($product->product_image);
//                }
                // Delete related stock records first.
                $product->stocks()->delete();
                // Then, soft delete the product itself.
                $product->delete();
            }
        });

        $this->auditLogService->log(
            $request,
            'Deleted',
            "User performed a bulk delete on products: {$deletedProductNames}.",
            $productsToDelete,
        );

        return response()->json(['message' => 'Selected products have been deleted successfully.']);
    }

    /**
     * Check product status
     */
    public function toggleStatus(Product $product)
    {
        $this->authorize('update', $product);
        $company = $product->company;
        $newStatus = $product->status === 'Active' ? 'Inactive' : 'Active';

        if ($newStatus === 'Active' && optional(optional($company->subscription)->plan)->product_limit !== null) {
            $limit = $company->subscription->plan->product_limit;
            if (Product::where('company_id', $company->company_id)->where('status', 'Active')->count() >= $limit) {
                return response()->json(['message' => "You have reached your plan's limit of {$limit} active products."], 422);
            }
        }

        $product->status = $newStatus;
        $product->save();

        return new ProductResource($product);
    }

    /**
     * Get the paginated purchase history for all products with the same name.
     */
    public function getPurchaseHistory(Request $request, Product $product)
    {
        $this->authorize('view', $product); // First, ensure the user can view this product

        // Find all product IDs with the same name for this company
        $allProductIdsWithName = Product::where('company_id', $product->company_id)
            ->where('product_name', $product->product_name)
            ->pluck('product_id');

        // Start a query for all stock records related to those IDs
        $purchasesQuery = \App\Models\Stock::whereIn('product_id', $allProductIdsWithName)
            ->with('product.category');

        // Apply sorting
        if ($request->input('sort_by') === 'oldest') {
            $purchasesQuery->orderBy('stock_purchase_date', 'asc');
        } else {
            $purchasesQuery->orderBy('stock_purchase_date', 'desc'); // Default to newest
        }

        // Paginate the final results
        $purchases = $purchasesQuery->paginate(10)->withQueryString();

        return StockResource::collection($purchases);
    }
}
