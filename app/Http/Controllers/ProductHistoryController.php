<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductHistoryController extends Controller
{
    /**
     * Display the purchase history for all products with a given name.
     */
    public function show(Request $request, $productName)
    {
        $companyId = Auth::user()->company_id;

        $productIds = Product::where('product_name', $productName)
            ->where('company_id', $companyId)
            ->pluck('product_id');

        // Start the query for stock records
        $query = Stock::whereIn('product_id', $productIds)->with('product.category');

        // --- SEARCH ---
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            // Search on the related product's SKU
            $query->whereHas('product', function($q) use ($searchTerm) {
                $q->where('product_SKU', 'LIKE', "%{$searchTerm}%");
            });
        }

        // --- APPLY SORTING LOGIC ---
        if ($request->input('sort_by') == 'oldest') {
            $query->orderBy('stock_purchase_date', 'asc');
        } else {
            // Default to newest first
            $query->orderBy('stock_purchase_date', 'desc');
        }

        $purchases = $query->with('product.category')->get();

        return view('products.history', compact('productName', 'purchases'));
    }
}
