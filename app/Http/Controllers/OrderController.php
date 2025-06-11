<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companyId = Auth::user()->company_id;

        // Start a base query for orders
        $query = Order::where('company_id', $companyId);

        // --- SEARCH ---
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            // This logic searches in the order ID column AND the related customer's name column
            $query->where(function ($q) use ($searchTerm) {
                $q->where('order_id', 'like', "%{$searchTerm}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($searchTerm) {
                        $customerQuery->where('customer_name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // --- APPLY FILTERS ---
        // Filter by status if the "Show paid only" box is checked
        if ($request->filled('show_paid_only')) {
            $query->where('order_status', 'Paid');
        }

        // --- APPLY SORTING ---
        $sortBy = $request->input('sort_by', 'date_desc'); // Default to newest first
        switch ($sortBy) {
            case 'date_asc':
                $query->orderBy('order_date', 'asc');
                break;
            case 'total_desc':
                $query->orderBy('total_amount', 'desc');
                break;
            case 'total_asc':
                $query->orderBy('total_amount', 'asc');
                break;
            default:
                $query->orderBy('order_date', 'desc');
                break;
        }

        // Paginate the final results and IMPORTANTLY, append the query string to the links
        $orders = $query->with('customer')->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // 1. Authorize: Make sure the user can only see orders from their own company.
        if ($order->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        // 2. Eager load all necessary relationships for the view to prevent extra database queries.
        $order->load(['customer', 'orderItems.product']);

        // 3. Pass the fully loaded order object to the view.
        return view('orders.show', compact('order'));
    }

    /**
     * Cancel the order
     *
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Order $order)
    {
        // Ensure user can only cancel orders from their own company
        if ($order->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        // Only paid orders that are canceled need their stock returned
        if ($order->order_status === 'Paid') {
            // Loop through each item in the order
            foreach ($order->orderItems as $item) {
                // Find the corresponding stock record and increment the quantity
                Stock::where('product_id', $item->product_id)->increment('stock_quantity', $item->order_item_quantity);
            }
        }

        // Update the order's status to Canceled
        $order->order_status = 'Canceled';
        $order->save();

        return redirect()->route('orders.index')->with('status', "Order #{$order->order_id} has been canceled.");
    }

    /**
     * Export .csv
     *
     * @param Order $order
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCsv(Order $order)
    {
        if ($order->company_id !== Auth::user()->company_id) { abort(403); }

        $order->load('orderItems.product');

        $fileName = "order-{$order->order_id}-details.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($order) {
            $file = fopen('php://output', 'w');
            // Add CSV Header
            fputcsv($file, ['Product SKU', 'Product Name', 'Quantity', 'Unit Price', 'Subtotal']);
            // Add Data
            foreach ($order->orderItems as $item) {
                fputcsv($file, [
                    $item->product->product_SKU,
                    $item->product->product_name,
                    $item->order_item_quantity,
                    $item->order_item_unit_price,
                    $item->order_item_quantity * $item->order_item_unit_price
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
