<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Since this is for the front-app dashboard, it's scoped to the logged-in user's company
        $user = $request->user();
        $companyId = $user->company_id;

        // --- OVERVIEW CARDS ---
        $products = Product::where('company_id', $companyId)->with('stocks')->get();
        $overview = [
            'totalProducts' => $products->count(),
            'inStock' => $products->filter(fn($p) => $p->stock_status === 'In Stock')->count(),
            'lowStock' => $products->filter(fn($p) => $p->stock_status === 'Low Stock')->count(),
            'outOfStock' => $products->filter(fn($p) => $p->stock_status === 'Out of Stock')->count(),
        ];

        // --- ORDER STATUS CHART ---
        $orderStatusCounts = Order::where('company_id', $companyId)
            ->select('order_status', DB::raw('count(*) as count'))
            ->groupBy('order_status')->pluck('count', 'order_status');

        // --- INVENTORY VALUE TREND CHART ---
        $startDate = now()->subMonths(5)->startOfMonth();
        $stockIn = Stock::where('company_id', $companyId)
            ->where('stock_purchase_date', '>=', $startDate)
            ->select(DB::raw('SUM(purchase_price * stock_quantity) as total, DATE_FORMAT(stock_purchase_date, "%b") as month, MIN(stock_purchase_date) as month_date'))
            ->groupBy('month')->orderBy('month_date')->pluck('total', 'month');

        $stockOut = \App\Models\OrderItem::whereHas('product', fn($q) => $q->where('company_id', $companyId))
            ->whereHas('order', fn($q) => $q->where('order_status', 'Paid')->where('order_date', '>=', $startDate))
            ->join('stock', 'order_item.product_id', '=', 'stock.product_id')
            ->join('orders', 'order_item.order_id', '=', 'orders.order_id')
            ->select(DB::raw('SUM(stock.purchase_price * order_item.order_item_quantity) as total, DATE_FORMAT(orders.order_date, "%b") as month, MIN(orders.order_date) as month_date'))
            ->groupBy('month')->orderBy('month_date')->pluck('total', 'month');

        $chartLabels = collect([]);
        for ($i = 5; $i >= 0; $i--) { $chartLabels->push(now()->subMonths($i)->format('M')); }
        $stockInData = $chartLabels->map(fn($month) => $stockIn[$month] ?? 0);
        $stockOutData = $chartLabels->map(fn($month) => $stockOut[$month] ?? 0);

        // --- RECENT ACTIVITY ---
        $recentActivity = AuditLog::where('entity_affected', 'Product')
            ->whereHasMorph('user', [CompanyAdmin::class, CompanyStaff::class], fn($q) => $q->where('company_id', $companyId))
            ->whereHas('subject')
            ->with(['user', 'subject'])
            ->latest('timestamp')
            ->limit(5)
            ->get();

        // --- PREPARE API RESPONSE ---
        return response()->json([
            'data' => [
                'overview' => $overview,
                'orderStatus' => [
                    'counts' => $orderStatusCounts,
                    'total' => $orderStatusCounts->sum()
                ],
                'inventoryValue' => [
                    'labels' => $chartLabels,
                    'stockIn' => $stockInData,
                    'stockOut' => $stockOutData,
                ],
                'recentActivity' => $recentActivity->map(function ($log) {
                    $subject = $log->subject;
                    $productName = null;
                    $productSku = null;
                    $currentStock = null;
                    $stockStatus = null;

                    // Only get product details if the subject exists and is a Product
                    if ($subject && $subject instanceof \App\Models\Product) {
                        $productName = $subject->product_name;
                        $productSku = $subject->product_SKU;
                        $currentStock = $subject->stocks->sum('stock_quantity');
                        $stockStatus = $subject->stock_status;
                    }

                    return [
                        'userName' => optional($log->user)->name ?? 'System',
                        'action' => $log->action,
                        'product_image_url' => optional($log->subject)->product_image ? \Illuminate\Support\Facades\Storage::disk('public')->url($log->subject->product_image) : null,
                        'entity' => $log->entity_affected,
                        'detail' => $log->details,
                        'timestamp' => $log->timestamp,
                        'subject_name' => $productName,
                        'subject_sku' => $productSku,
                        'current_stock' => $currentStock,
                        'stock_status' => $stockStatus,
                    ];
                }),
            ]
        ]);
    }
}
