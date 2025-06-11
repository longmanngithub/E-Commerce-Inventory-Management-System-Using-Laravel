<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        // Start a base query for products belonging to the user's company
        $query = Product::where('company_id', Auth::user()->company_id);

        // --- SEARCH ---
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('product_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('product_SKU', 'LIKE', "%{$searchTerm}%");
            });
        }

        // --- APPLY FILTERS ---
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }
        if ($request->filled('in_stock_only')) {
            $query->whereHas('stocks', function ($q) {
                $q->where('stock_quantity', '>', 0);
            });
        }

        // --- APPLY SORTING ---
        if ($request->filled('sort_by')) {
            if ($request->sort_by == 'price_asc') {
                $query->orderBy('product_price', 'asc');
            } elseif ($request->sort_by == 'price_desc') {
                $query->orderBy('product_price', 'desc');
            }
        } else {
            // CORRECTED DEFAULT SORT: Order by the newest product ID first.
            $query->orderBy('product_id', 'desc');
        }

        // Get all categories for the filter form
        $categories = \App\Models\Category::all();

        // Paginate the final results
        $products = $query->with(['category', 'stocks'])->paginate(10)->withQueryString();

        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        // LATER, we will add a permission check here like:
        // if (Auth::user()->cannot('create-products')) { abort(403); }

        // 1. Validation remains the same and is correct.
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:128',
            'product_SKU' => 'required|string|unique:product,product_SKU',
            'category_id' => 'required|integer|exists:category,category_id',
            'stock_quantity' => 'required|integer|min:0',
            'product_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date|before_or_equal:today',
            'product_expiry_date' => 'nullable|date',
            'product_desc' => 'nullable|string',
            'product_image' => 'nullable|image|max:2048',
            'purchase_price' => 'required|numeric|min:0'
        ]);

        $companyId = auth()->user()->company_id;
        $imagePath = $request->hasFile('product_image') ? $request->file('product_image')->store('product-images', 'public') : null;

        // 2. Create the Product. NOTE: 'purchase_price' has been REMOVED from this array.
        $product = Product::create([
            'company_id' => $companyId,
            'category_id' => $validatedData['category_id'],
            'product_name' => $validatedData['product_name'],
            'product_SKU' => $validatedData['product_SKU'],
            'product_price' => $validatedData['product_price'],
            'product_desc' => $validatedData['product_desc'],
            'product_image' => $imagePath,
            'product_expiry_date' => $validatedData['product_expiry_date'],
        ]);

        // 3. Create the initial Stock record. NOTE: 'purchase_price' has been ADDED here.
        Stock::create([
            'product_id' => $product->product_id,
            'stock_quantity' => $validatedData['stock_quantity'],
            'stock_purchase_date' => $validatedData['purchase_date'],
            'purchase_price' => $validatedData['purchase_price'], // <-- THE FIX
            'company_id' => $companyId,
        ]);

        return redirect()->route('products.index')->with('status', 'Product added successfully!');
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        // Fetch ALL categories from the database
        $categories = \App\Models\Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        // Because of Route-Model Binding, Laravel automatically finds the
        // product from the database based on the ID in the URL.

        // We also need to fetch all categories for the dropdown menu.
        $categories = \App\Models\Category::all();

        // Pass both the product and the categories to the view.
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product and its stock in storage.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validate only the fields that are editable.
        $validatedData = $request->validate([
            'product_price' => 'required|numeric|min:0',
            'product_desc' => 'nullable|string', // Description is still validated and saved
            'stock_quantity' => 'nullable|integer|min:0',
            'product_image' => 'nullable|image|max:2048',
        ]);

        // 2. Update the Product model with its editable fields.
        $product->update($request->only([
            'product_price',
            'product_desc',
        ]));

        // 3. Find and update the most recent stock record's quantity if provided
        $latestStock = $product->stocks()->latest('stock_purchase_date')->first();
        if ($latestStock && $request->filled('stock_quantity')) {
            $latestStock->update([
                'stock_quantity' => $request->stock_quantity,
            ]);
        }

        // 4. Handle image update
        if ($request->hasFile('product_image')) {
            // Optional: Delete the old image to save space
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }
            // Store the new image and update the database path
            $imagePath = $request->file('product_image')->store('product-images', 'public');
            $product->update(['product_image' => $imagePath]);
        }

        return redirect()->route('products.index')->with('status', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete-product', $product);

        // 1. Delete the product's image from storage to keep things clean.
        //    We check if an image exists before trying to delete it.
        if ($product->product_image) {
            Storage::disk('public')->delete($product->product_image);
        }

        // 2. Delete any related stock records.
        //    This assumes a 'stock' relationship is defined on your Product model.
        $product->stocks()->delete();

        // 3. Delete the product itself.
        $product->delete();

        // 4. Redirect back to the product list with a success message.
        return redirect()->route('products.index')->with('status', 'Product deleted successfully!');
    }

    /**
     * Display the specified product and its purchase history.
     */
    public function show(Product $product)
    {
        // First, we ensure the user is allowed to see this product.
        if ($product->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        // Load the purchase history for this product using the 'stocks' relationship.
        // Make sure the 'stocks' relationship exists in your Product model.
        $purchases = $product->stocks()->latest('stock_purchase_date')->get();

        // This line sends the specific $product and its $purchases to the view.
        return view('products.show', compact('product', 'purchases'));
    }

    /**
     * Bulk delete function
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDestroy(Request $request)
    {
        // Authorize the action before doing anything else
        $this->authorize('bulk-delete-products', Product::class);

        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:product,product_id',
        ]);

        $productIds = $request->input('product_ids');

        // Find all products to delete to get their image paths
        $productsToDelete = Product::whereIn('product_id', $productIds)->get();
        foreach ($productsToDelete as $product) {
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }
        }

        // Delete related stock records and then the products themselves
        Stock::whereIn('product_id', $productIds)->delete();
        Product::whereIn('product_id', $productIds)->delete();

        return redirect()->route('products.index')->with('status', 'Selected products have been deleted successfully!');
    }
}
