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
use App\Services\AuditLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CompanyAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        // --- OVERVIEW CARDS ---
        $totalRevenue = Order::where('company_id', $companyId)->where('order_status', 'Paid')->sum('total_amount');
        $totalProfit = OrderItem::whereHas('product', fn($q) => $q->where('company_id', $companyId))->get()->sum('profit');
        $netPurchaseValue = Stock::where('company_id', $companyId)->sum(DB::raw('purchase_price * stock_quantity'));
        $yoyProfit = OrderItem::whereHas('product', fn($q) => $q->where('company_id', $companyId))
            ->whereHas('order', fn($q) => $q->where('order_status', 'Paid')->where('order_date', '>=', now()->subYear()))
            ->get()->sum('profit');

        // --- BEST SELLING CATEGORY ---
        // First, find the top categories based on all-time turnover
        $topCategories = Category::join('product', 'category.category_id', '=', 'product.category_id')
            ->join('order_item', 'product.product_id', '=', 'order_item.product_id')
            ->where('product.company_id', $companyId)
            ->select('category.category_id', 'category.category_name')
            ->groupBy('category.category_id', 'category.category_name')
            ->orderByRaw('SUM(order_item.order_item_quantity * order_item.order_item_unit_price) DESC')
            ->limit(3)
            ->get();

        // Now, for each top category, calculate the monthly turnover and percentage change
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
        $timeRange = $request->input('time_range', '6m'); // Default to 6 months
        $chartLabels = collect([]);
        $startDate = now();
        $phpDateFormat = 'M Y';
        $sqlDateFormat = '%b %Y';

        switch ($timeRange) {
            case '1y':
                $startDate = now()->subYear()->startOfMonth();
                for ($i = 11; $i >= 0; $i--) { $chartLabels->push(now()->subMonths($i)->format($phpDateFormat)); }
                break;
            case '30d':
                $startDate = now()->subDays(29)->startOfDay();
                $phpDateFormat = 'M d';
                $sqlDateFormat = '%b %d';
                for ($i = 29; $i >= 0; $i--) { $chartLabels->push(now()->subDays($i)->format($phpDateFormat)); }
                break;
            default: // '6m'
                $startDate = now()->subMonths(5)->startOfMonth();
                for ($i = 5; $i >= 0; $i--) { $chartLabels->push(now()->subMonths($i)->format($phpDateFormat)); }
                break;
        }

        // --- BEST SELLING PRODUCT ---
        $topProducts = Product::where('product.company_id', $companyId)
            ->join('order_item', 'product.product_id', '=', 'order_item.product_id')
            ->select('product.*', DB::raw('SUM(order_item.order_item_quantity * order_item.order_item_unit_price) as total_turnover'))
            ->groupBy('product.product_id')->orderBy('total_turnover', 'desc')->limit(5)->get();

        $bestSellingProducts = $topProducts->map(function ($product) {
            $turnoverThisMonth = $product->orderItems()->whereHas('order', fn($q) => $q->whereBetween('order_date', [now()->subDays(30), now()]))->sum(DB::raw('order_item_quantity * order_item_unit_price'));
            $turnoverLastMonth = $product->orderItems()->whereHas('order', fn($q) => $q->whereBetween('order_date', [now()->subDays(60), now()->subDays(30)]))->sum(DB::raw('order_item_quantity * order_item_unit_price'));
            $turnoverChange = ($turnoverLastMonth > 0) ? (($turnoverThisMonth - $turnoverLastMonth) / $turnoverLastMonth) * 100 : 0;

            return [
                'name' => $product->product_name,
                'image_url' => $product->product_image ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->product_image) : null,
                'sku' => $product->product_SKU,
                'category' => optional($product->category)->category_name,
                'remaining_quantity' => $product->stocks->sum('stock_quantity'),
                'turnover' => $turnoverThisMonth,
                'increased_by' => $turnoverChange,
            ];
        });

        // --- CHART QUERIES ---
        $revenueByDate = Order::where('company_id', $companyId)->where('order_status', 'Paid')->where('order_date', '>=', $startDate)
            ->select(DB::raw("SUM(total_amount) as amount, DATE_FORMAT(order_date, '$sqlDateFormat') as date_key"))
            ->groupBy('date_key')->get()->pluck('amount', 'date_key');

        $profitByDate = OrderItem::whereHas('product', fn($q) => $q->where('company_id', $companyId))
            ->whereHas('order', fn($q) => $q->where('order_status', 'Paid')->where('order_date', '>=', $startDate))
            ->get()->groupBy(fn($item) => Carbon::parse($item->order->order_date)->format($phpDateFormat))
            ->map(fn($group) => $group->sum('profit'));

        // Package all data into a single array
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

        // Return the data through the resource
        return new AnalyticsReportResource($analyticsData);
    }

    /**
     * Generate and stream an analytics report as a CSV file.
     */
    public function exportCsv(Request $request, Company $company): StreamedResponse
    {
        $companyId = $request->user()->company_id;
        $fileName = "analytics_report_" . now()->format('Y-m-d') . ".csv";

        // Set the headers to force a file download
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Use a callback to generate the CSV content on the fly
        $callback = function() use ($companyId) {
            $file = fopen('php://output', 'w');

            // Re-calculate the data needed for the CSV
            $totalRevenue = \App\Models\Order::where('company_id', $companyId)->where('order_status', 'Paid')->sum('total_amount');
            $totalProfit = \App\Models\OrderItem::whereHas('product', fn($q) => $q->where('company_id', $companyId))->get()->sum('profit');

            $bestSellingProducts = \App\Models\Product::where('product.company_id', $companyId)
                ->join('order_item', 'product.product_id', '=', 'order_item.product_id')
                ->select('product.product_name', 'product.product_SKU', DB::raw('SUM(order_item.order_item_quantity) as total_sold'))
                ->groupBy('product.product_id', 'product.product_name', 'product.product_SKU')
                ->orderBy('total_sold', 'desc')->limit(5)->get();

            // Add rows for overview stats
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Revenue', $totalRevenue]);
            fputcsv($file, ['Total Profit', $totalProfit]);
            fputcsv($file, []); // Blank line for spacing

            // Add rows for best selling products
            fputcsv($file, ['Best Selling Products', 'SKU', 'Total Units Sold']);
            foreach ($bestSellingProducts as $product) {
                fputcsv($file, [$product->product_name, $product->product_SKU, $product->total_sold]);
            }

            fclose($file);
        };

        // Return the response that streams the data
        return new StreamedResponse($callback, 200, $headers);
    }
}
