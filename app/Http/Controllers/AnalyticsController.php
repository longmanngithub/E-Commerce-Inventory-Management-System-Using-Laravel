<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics report page.
     */
    public function index()
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
        $startDate = Carbon::now()->subMonths(5)->startOfMonth(); // Go back 6 months including current

        // Get Revenue per month
        $revenueData = Order::where('company_id', $companyId)
            ->where('order_status', 'Paid')
            ->where('order_date', '>=', $startDate)
            ->select(
                DB::raw('SUM(total_amount) as amount'),
                DB::raw('DATE_FORMAT(order_date, "%b") as month')
            )
            ->groupBy('month')
            ->orderBy('order_date', 'asc')
            ->get()->pluck('amount', 'month');

        // Get Profit per month
        $orderItems = OrderItem::whereHas('product', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
            ->whereHas('order', function ($query) use ($startDate) {
                $query->where('order_status', 'Paid')->where('order_date', '>=', $startDate);
            })
            ->with('order')
            ->get();

        $profitData = $orderItems->groupBy(function ($item) {
            return Carbon::parse($item->order->order_date)->format('M');
        })->map(function ($group) {
            return $group->sum('profit');
        });

        // Create labels for the last 6 months to ensure all months are present
        $chartLabels = collect([]);
        for ($i = 5; $i >= 0; $i--) {
            $chartLabels->push(Carbon::now()->subMonths($i)->format('M'));
        }

        // Map the data to the labels, filling in 0 for months with no data
        $chartRevenue = $chartLabels->map(fn ($month) => $revenueData[$month] ?? 0);
        $chartProfit = $chartLabels->map(fn ($month) => $profitData[$month] ?? 0);
        // Note: Calculating profit per month would require a similarly complex query.
        // For now, we will just chart revenue.


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
            'totalRevenue',
            'totalProfit',
            'bestSellingCategories',
            'chartLabels',
            'chartRevenue',
            'bestSellingProducts'
        ));
    }
}
