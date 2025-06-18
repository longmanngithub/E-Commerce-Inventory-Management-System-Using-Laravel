<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Stock;
use App\Services\AuditLogService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(protected AuditLogService $auditLogService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start the base query for orders belonging to the user's company
        $query = Order::where('company_id', $request->user()->company_id)->with('customer');

        // Filter by Order ID (Search)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                // Search in the order ID column
                $q->where('order_id', 'like', "%{$searchTerm}%")
                    // Also, search the related customer's name column
                    ->orWhereHas('customer', function ($customerQuery) use ($searchTerm) {
                        $customerQuery->where('customer_name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Filter by Order Status
        if ($request->filled('status') && in_array($request->status, ['Paid', 'Canceled'])) {
            $query->where('order_status', $request->status);
        }

        // Filter by Date Range
        $sortBy = $request->input('sort_by');

        // Sort by
        if ($sortBy === 'price_asc') {
            $query->orderBy('total_amount', 'asc');
        } elseif ($sortBy === 'price_desc') {
            $query->orderBy('total_amount', 'desc');
        } elseif ($sortBy === 'date_asc') {
            $query->orderBy('order_date', 'asc');
        } else {
            // Default sort: latest date first
            $query->orderBy('order_date', 'desc');
        }

        $orders = $query->orderBy('order_date', 'desc')->paginate(10);

        return OrderResource::collection($orders);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return new OrderDetailResource($order->load('customer', 'orderItems.product'));
    }

    /**
     * Cancel the order.
     */
    public function cancel(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if ($order->order_status !== 'Paid') {
            return response()->json(['message' => 'Only paid orders can be canceled.'], 422);
        }

        foreach ($order->orderItems as $item) {
            $stock = Stock::where('product_id', $item->product_id)->latest('stock_purchase_date')->first();
            if ($stock) {
                $stock->increment('stock_quantity', $item->order_item_quantity);
            }
        }
        $order->order_status = 'Canceled';
        $order->save();

        $this->auditLogService->log($request, 'Updated', "Canceled Order #{$order->order_id}",  $order);

        return response()->json(['message' => 'Order has been canceled and stock restored.']);
    }

    /**
     * Generate and stream a CSV for a single order.
     */
    public function exportSingleOrderCsv(Request $request, Order $order)
    {
        $this->authorize('view', $order); // Authorize the action

        $fileName = "order_{$order->order_id}_details.csv";
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        // Eager load the relationships
        $order->load('orderItems.product');

        $callback = function() use ($order) {
            $file = fopen('php://output', 'w');

            // Add header row
            fputcsv($file, ['Product Name', 'SKU', 'Quantity', 'Price', 'Subtotal']);

            // Add item rows
            foreach ($order->orderItems as $item) {
                fputcsv($file, [
                    $item->product->product_name,
                    $item->product->product_SKU,
                    $item->order_item_quantity,
                    $item->order_item_unit_price,
                    $item->order_item_quantity * $item->order_item_unit_price
                ]);
            }
            fclose($file);
        };

        $this->auditLogService->log($request, 'Exported', "Exported details for Order #{$order->order_id}", $order);

        return new StreamedResponse($callback, 200, $headers);
    }
}
