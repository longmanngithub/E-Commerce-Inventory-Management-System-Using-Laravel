<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnalyticsReportResource;
use App\Models\Category;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CompanyAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;
        
        // --- 1. COMPATIBILITY SETUP ---
        // Detect database driver to handle Date Formatting differences
        $driver = DB::connection()->getDriverName(); // 'mysql' or 'pgsql'
        $isPgsql = $driver === 'pgsql';

        // --- OVERVIEW CARDS ---
        // (These use standard Eloquent/Carbon and work everywhere automatically)
        $totalRevenue = Order::where('company_id', $companyId)->where('order_status', 'Paid')->sum('total_amount');
        $totalProfit = OrderItem::whereHas('product', fn($q) => $q->where('company_id', $companyId))->get()->sum('profit');
        $netPurchaseValue = Stock::where('company_id', $companyId)->sum(DB::raw('purchase_price * stock_quantity'));
        $yoyProfit = OrderItem::whereHas('product', fn($q) => $q->where('company_id', $companyId))
            ->whereHas('order', fn($q) => $q->where('order_status', 'Paid')->where('order_date', '>=', now()->subYear()))
            ->get()->sum('profit');

        // --- BEST SELLING CATEGORY ---
        // We group by explicit columns to satisfy "Strict Mode" in both DBs
        $topCategories = Category::join('product', 'category.category_id', '=', 'product.category_id')
            ->join('order_item', 'product.product_id', '=', 'order_item.product_id')
            ->where('product.company_id', $companyId)
            ->select('category.category_id', 'category.category_name')
            ->groupBy('category.category_id', 'category.category_name')
            ->orderByRaw('SUM(order_item.order_item_quantity * order_item.order_item_unit_price) DESC')
            ->limit(3)
            ->get();

        $bestSellingCategories = $topCategories->map(function ($category) use ($companyId) {
            $turnoverThisMonth = OrderItem::whereHas('product.category', fn($q) => $q->where('category.category_id', $category->category_id))
                ->whereHas('order', fn($q) => $q->where('company_id', $companyId)->whereBetween('order_date', [now()->startOfMonth(), now()->endOfMonth()]))
                ->sum(DB::raw('order_item_quantity * order_item_unit_price'));

            $turnoverLastMonth = OrderItem::whereHas('product.category', fn($q) => $q->where('category.category_id', $category->category_id))
                ->whereHas('order', fn($q) => $q->where('company_id', $companyId)->whereBetween('order_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]))
                ->sum(DB::raw('order_item_quantity * order_item_unit_price'));

            $increaseBy = ($turnoverLastMonth > 0) ? (($turnoverThisMonth - $turnoverLastMonth) / $turnoverLastMonth) * 100 : 0;

            return [
                'category_name' => $category->category_name,
                'turnover' => $turnoverThisMonth,
                'increase_by' => $increaseBy,
            ];
        });

        // --- PROFIT & REVENUE CHART DATA ---
        $timeRange = $request->input('time_range', '6m');
        $chartLabels = collect([]);
        $startDate = now();
        $phpDateFormat = 'M Y';
        
        // Define SQL Format based on Driver
        if ($isPgsql) {
            // PostgreSQL uses TO_CHAR()
            $sqlDateFunction = "TO_CHAR";
            $sqlFormatYear = "Mon YYYY"; 
            $sqlFormatDay = "Mon DD";    
        } else {
            // MySQL uses DATE_FORMAT()
            $sqlDateFunction = "DATE_FORMAT";
            $sqlFormatYear = "%b %Y";
            $sqlFormatDay = "%b %d";
        }

        $activeSqlFormat = $sqlFormatYear; // Default

        switch ($timeRange) {
            case '1y':
                $startDate = now()->subYear()->startOfMonth();
                for ($i = 11; $i >= 0; $i--) { $chartLabels->push(now()->subMonths($i)->format($phpDateFormat)); }
                $activeSqlFormat = $sqlFormatYear;
                break;
            case '30d':
                $startDate = now()->subDays(29)->startOfDay();
                $phpDateFormat = 'M d';
                $activeSqlFormat = $sqlFormatDay;
                for ($i = 29; $i >= 0; $i--) { $chartLabels->push(now()->subDays($i)->format($phpDateFormat)); }
                break;
            default: // '6m'
                $startDate = now()->subMonths(5)->startOfMonth();
                for ($i = 5; $i >= 0; $i--) { $chartLabels->push(now()->subMonths($i)->format($phpDateFormat)); }
                $activeSqlFormat = $sqlFormatYear;
                break;
        }

        // --- CHART QUERIES (Dynamic SQL) ---
        $revenueByDate = Order::where('company_id', $companyId)
            ->where('order_status', 'Paid')
            ->where('order_date', '>=', $startDate)
            ->select(DB::raw("SUM(total_amount) as amount, {$sqlDateFunction}(order_date, '{$activeSqlFormat}') as date_key"))
            ->groupBy('date_key')
            ->get()
            ->pluck('amount', 'date_key');

        // Profit is calculated in PHP (collection logic), so it's already safe/compatible.
        $profitByDate = OrderItem::whereHas('product', fn($q) => $q->where('company_id', $companyId))
            ->whereHas('order', fn($q) => $q->where('order_status', 'Paid')->where('order_date', '>=', $startDate))
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->order->order_date)->format($phpDateFormat))
            ->map(fn($group) => $group->sum('profit'));

        // --- BEST SELLING PRODUCT ---
        // COMPATIBILITY FIX: Use addSelect (Subquery) instead of Join+GroupBy
        // This solves "column must appear in GROUP BY" errors.
        $topProducts = Product::where('company_id', $companyId)
            ->select('product.*')
            ->addSelect(['total_turnover' => OrderItem::selectRaw('SUM(order_item_quantity * order_item_unit_price)')
                ->whereColumn('product_id', 'product.product_id')
            ])
            ->orderByDesc('total_turnover')
            ->limit(5)
            ->get();

        $bestSellingProducts = $topProducts->map(function ($product) {
            // Note: Reuse the Turnover subquery logic or keep using relation queries here
            $turnoverThisMonth = $product->orderItems()->whereHas('order', fn($q) => $q->whereBetween('order_date', [now()->subDays(30), now()]))->sum(DB::raw('order_item_quantity * order_item_unit_price'));
            $turnoverLastMonth = $product->orderItems()->whereHas('order', fn($q) => $q->whereBetween('order_date', [now()->subDays(60), now()->subDays(30)]))->sum(DB::raw('order_item_quantity * order_item_unit_price'));
            $turnoverChange = ($turnoverLastMonth > 0) ? (($turnoverThisMonth - $turnoverLastMonth) / $turnoverLastMonth) * 100 : 0;

            return [
                'name' => $product->product_name,
                'image_url' => $product->product_image ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->product_image) : null,
                'sku' => $product->product_SKU,
                'category' => optional($product->category)->category_name,
                'remaining_quantity' => $product->stocks->sum('stock_quantity'),
                'turnover' => $turnoverThisMonth, // Or use $product->total_turnover if you want All Time
                'increased_by' => $turnoverChange,
            ];
        });

        // Package Data
        $analyticsData = [
            'overview' => [
                'totalProfit' => $totalProfit, 'totalRevenue' => $totalRevenue, 'sales' => $totalRevenue,
                'netPurchaseValue' => $netPurchaseValue, 'netSalesValue' => $totalRevenue, 'yoyProfit' => $yoyProfit,
            ],
            'charts' => [
                'profitAndRevenue' => [
                    'labels' => $chartLabels,
                    'revenue' => $chartLabels->map(fn($label) => $revenueByDate[$label] ?? 0),
                    'profit' => $chartLabels->map(fn($label) => $profitByDate[$label] ?? 0),
                ]
            ],
            'bestSellingCategories' => $bestSellingCategories,
            'bestSellingProducts' => $bestSellingProducts,
        ];

        return new AnalyticsReportResource($analyticsData);
    }

    public function exportCsv(Request $request, Company $company): StreamedResponse
    {
        $companyId = $request->user()->company_id;
        $fileName = "analytics_report_" . now()->format('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($companyId) {
            $file = fopen('php://output', 'w');

            $totalRevenue = Order::where('company_id', $companyId)->where('order_status', 'Paid')->sum('total_amount');
            $totalProfit = OrderItem::whereHas('product', fn($q) => $q->where('company_id', $companyId))->get()->sum('profit');

            // COMPATIBILITY FIX: Use subquery for CSV export as well to avoid Group By issues
            $bestSellingProducts = Product::where('company_id', $companyId)
                ->select('product_name', 'product_SKU')
                ->addSelect(['total_sold' => OrderItem::selectRaw('SUM(order_item_quantity)')
                    ->whereColumn('product_id', 'product.product_id')
                ])
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();

            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Revenue', $totalRevenue]);
            fputcsv($file, ['Total Profit', $totalProfit]);
            fputcsv($file, []); 

            fputcsv($file, ['Best Selling Products', 'SKU', 'Total Units Sold']);
            foreach ($bestSellingProducts as $product) {
                // Ensure total_sold defaults to 0 if null
                fputcsv($file, [$product->product_name, $product->product_SKU, $product->total_sold ?? 0]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}