<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;
        $startDate = now()->subMonths(5)->startOfMonth();

        // --- DATA FOR OVERVIEW CARDS ---
        $products = Product::where('company_id', $companyId)->with('stocks')->get();
        $totalProducts = $products->count();
        $inStock = $products->filter(fn($p) => $p->stock_status === 'In Stock')->count();
        $lowStock = $products->filter(fn($p) => $p->stock_status === 'Low Stock')->count();
        $outOfStock = $products->filter(fn($p) => $p->stock_status === 'Out of Stock')->count();

        // --- DATA FOR ORDER STATUS CHART ---
        $orderStatusCounts = Order::where('company_id', $companyId)
            ->select('order_status', DB::raw('count(*) as count'))
            ->groupBy('order_status')
            ->pluck('count', 'order_status');

        // --- Data for Inventory Value Trend Chart ---
        $stockIn = Stock::where('company_id', $companyId)
            ->where('stock_purchase_date', '>=', $startDate)
            ->select(
                DB::raw('SUM(purchase_price * stock_quantity) as total'),
                DB::raw('DATE_FORMAT(stock_purchase_date, "%b") as month'),
                // THE FIX, PART 1: Get the first date of each month group for sorting
                DB::raw('MIN(stock_purchase_date) as month_date')
            )
            ->groupBy('month')
            // THE FIX, PART 2: Order by our new, unambiguous month_date column
            ->orderBy('month_date', 'asc')
            ->pluck('total', 'month');

        $stockOut = OrderItem::whereHas('product', fn($q) => $q->where('company_id', $companyId))
            ->whereHas('order', fn($q) => $q->where('order_status', 'Paid')->where('order_date', '>=', $startDate))
            ->join('stock', 'order_item.product_id', '=', 'stock.product_id')
            ->select(
                DB::raw('SUM(stock.purchase_price * order_item.order_item_quantity) as total'),
                DB::raw('DATE_FORMAT(orders.order_date, "%b") as month'),
                // THE FIX, PART 1: Get the first date of each month group for sorting
                DB::raw('MIN(orders.order_date) as month_date')
            )
            ->join('orders', 'order_item.order_id', '=', 'orders.order_id')
            ->groupBy('month')
            // THE FIX, PART 2: Order by our new, unambiguous month_date column
            ->orderBy('month_date', 'asc')
            ->pluck('total', 'month');

        // --- DATA FOR RECENT ACTIVITY ---
        // Get staff and admin IDs for this company
        $staffIds = CompanyStaff::where('company_id', $companyId)->pluck('staff_id');
        $adminIds = CompanyAdmin::where('company_id', $companyId)->pluck('admin_id');

        $recentActivity = AuditLog::where(function ($query) use ($staffIds, $adminIds) {
            $query->where('user_type', \App\Models\CompanyStaff::class)->whereIn('user_id', $staffIds)
                ->orWhere(function ($query) use ($adminIds) {
                    $query->where('user_type', \App\Models\CompanyAdmin::class)->whereIn('user_id', $adminIds);
                });
        })
            ->with(['user', 'subject'])
            ->latest('timestamp')
            ->limit(5)
            ->get();

        // Create labels for the last 6 months
        $chartLabels = collect([]);
        for ($i = 5; $i >= 0; $i--) {
            $chartLabels->push(now()->subMonths($i)->format('M'));
        }

        // Map the data to the labels
        $stockInData = $chartLabels->map(fn($month) => $stockIn[$month] ?? 0);
        $stockOutData = $chartLabels->map(fn($month) => $stockOut[$month] ?? 0);


        return view('dashboard', compact(
            'totalProducts', 'inStock', 'lowStock', 'outOfStock',
            'orderStatusCounts',
            'recentActivity',
            'chartLabels', 'stockInData', 'stockOutData'
        ));
    }
}
