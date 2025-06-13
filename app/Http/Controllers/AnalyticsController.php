<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics report page.
     */
    public function index(Request $request)
    {
        $companyId = Auth::user()->company_id;

        // --- OVERVIEW CARDS ---
        // Calculate total revenue from all "Paid" orders. In this context, Sales and Revenue are the same.
        $totalRevenue = Order::where('company_id', $companyId)
            ->where('order_status', 'Paid')
            ->sum('total_amount');

        // To calculate profit, we get all order items for the company's products and sum the 'profit' accessor we created.
        $totalProfit = OrderItem::whereHas('product', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->get()->sum('profit');

        // Calculate Net Purchase Value (total cost of all stock ever purchased)
        $netPurchaseValue = Stock::where('company_id', $companyId)->sum(DB::raw('purchase_price * stock_quantity'));

        // Simplified YoY Profit Calculation
        // Note: A true YoY calculation requires multiple years of data.
        // This is a simplified version showing profit from the last 12 months.
        $yoyProfit = OrderItem::whereHas('product', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->whereHas('order', function ($query) {
            $query->where('order_status', 'Paid')->where('order_date', '>=', Carbon::now()->subYear());
        })->get()->sum('profit');

        // For the purpose of the UI, "Sales" and "Net Sales Value" are often the same as Revenue.
        $sales = $totalRevenue;
        $netSalesValue = $totalRevenue;


        // --- BEST SELLING CATEGORY ---
        // This is a complex query that joins tables and groups by category to get the total turnover.
        $bestSellingCategories = Category::join('product', 'category.category_id', '=', 'product.category_id')
            ->join('order_item', 'product.product_id', '=', 'order_item.product_id')
            ->where('product.company_id', $companyId)
            ->select('category.category_name', DB::raw('SUM(order_item.order_item_quantity * order_item.order_item_unit_price) as turnover'))
            ->groupBy('category.category_name')
            ->orderBy('turnover', 'desc')
            ->limit(3)
            ->get();


        // --- PROFIT & REVENUE CHART DATA (Last 6 Months) ---
        $timeRange = $request->input('time_range', '6m');
        $chartLabels = collect([]);
        $startDate = now();

        // Determine date formats and labels
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
            default: // 6m
                $startDate = now()->subMonths(5)->startOfMonth();
                for ($i = 5; $i >= 0; $i--) { $chartLabels->push(now()->subMonths($i)->format($phpDateFormat)); }
                break;
        }

        // Get Revenue data
        $revenueByDate = Order::where('company_id', $companyId)
            ->where('order_status', 'Paid')
            ->where('order_date', '>=', $startDate)
            ->select(DB::raw("SUM(total_amount) as amount, DATE_FORMAT(order_date, '$sqlDateFormat') as date_key"))
            ->groupBy('date_key')
            ->get()->pluck('amount', 'date_key');

        // Get Profit data
        $profitByDate = OrderItem::whereHas('product', fn($q) => $q->where('company_id', $companyId))
            ->whereHas('order', fn($q) => $q->where('order_status', 'Paid')->where('order_date', '>=', $startDate))
            ->with('order:order_id,order_date')->get()
            ->groupBy(fn($item) => Carbon::parse($item->order->order_date)->format($phpDateFormat))
            ->map(fn($group) => $group->sum('profit'));

        // Map data to the final, ordered labels
        $chartRevenue = $chartLabels->map(fn($label) => $revenueByDate[$label] ?? 0);
        $chartProfit = $chartLabels->map(fn($label) => $profitByDate[$label] ?? 0);


        // --- BEST SELLING PRODUCT ---
        $bestSellingProducts = Product::where('product.company_id', $companyId)
            // Join with order_item to calculate revenue
            ->join('order_item', 'product.product_id', '=', 'order_item.product_id')
            // Eager load relationships to get category name and stock quantity
            ->with('category', 'stocks')
            ->select(
                'product.*', // Select all columns from the product table
                DB::raw('SUM(order_item.order_item_quantity * order_item.order_item_unit_price) as turnover')
            )
            ->groupBy('product.product_id') // Group by product to sum up the turnover
            ->orderBy('turnover', 'desc') // Order by the calculated turnover
            ->limit(5)
            ->get();


        // Pass all the calculated data to the view
        return view('analytics.index', compact(
            'totalRevenue', 'totalProfit', 'sales', 'netPurchaseValue', 'netSalesValue', 'yoyProfit',
            'bestSellingCategories', 'chartLabels', 'chartRevenue', 'chartProfit', 'bestSellingProducts'
        ));
    }

    /**
     * Export as CSV
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse|void
     */
    public function exportCsv()
    {
        $companyId = Auth::user()->company_id;
        $fileName = "analytics_report_" . now()->format('Y-m-d') . ".csv";

        // Re-calculate the data needed for the CSV
        $totalRevenue = Order::where('company_id', $companyId)->where('order_status', 'Paid')->sum('total_amount');
        $totalProfit = OrderItem::whereHas('product', fn($q) => $q->where('company_id', $companyId))->get()->sum('profit');
        $bestSellingProducts = Product::where('product.company_id', $companyId)
            ->join('order_item', 'product.product_id', '=', 'order_item.product_id')
            ->select('product.product_name', 'product.product_SKU', DB::raw('SUM(order_item.order_item_quantity) as total_sold'))
            ->groupBy('product.product_id', 'product.product_name', 'product.product_SKU')
            ->orderBy('total_sold', 'desc')->limit(5)->get();

        // Set the headers to force a file download
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Use a callback to generate the CSV content on the fly
        $callback = function() use ($totalRevenue, $totalProfit, $bestSellingProducts) {
            $file = fopen('php://output', 'w');

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

        return response()->stream($callback, 200, $headers);
    }
}
