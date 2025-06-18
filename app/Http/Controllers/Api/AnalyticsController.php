<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubscriptionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // --- OVERVIEW CARDS ---
        $totalUsers = CompanyAdmin::count() + CompanyStaff::count();
        $totalCompanies = Company::count();
        $totalProducts = Product::count();
        $totalOrders = Order::where('order_status', 'Paid')->count();

        // --- ACTIVE SUBSCRIPTIONS ---
        $subscriptionDistribution = SubscriptionOrder::where('is_paid', true)
            ->select('subscription_tier', DB::raw('count(*) as count'))
            ->groupBy('subscription_tier')
            ->pluck('count', 'subscription_tier');

        // --- BEST PERFORMING COMPANIES ---
        $bestPerformingCompaniesData = Company::with('admins', 'staff', 'products', 'orders')
            ->withSum(['orders' => fn($q) => $q->where('order_status', 'Paid')], 'total_amount')
            ->orderBy('orders_sum_total_amount', 'desc')
            ->limit(5)
            ->get();

        // Now, we format this data and calculate the monthly change for each company
        $bestPerformingCompanies = $bestPerformingCompaniesData->map(function ($company) {
            $revenueThisMonth = $company->orders()
                ->where('order_status', 'Paid')
                ->whereBetween('order_date', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('total_amount');

            $revenueLastMonth = $company->orders()
                ->where('order_status', 'Paid')
                ->whereBetween('order_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
                ->sum('total_amount');

            $revenueChange = ($revenueLastMonth > 0) ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 : 0;

            return [
                'name' => $company->company_name,
                'users' => $company->admins->count() + $company->staff->count(),
                'products' => $company->products->count(),
                'orders' => $company->orders->count(),
                'revenue' => $revenueThisMonth,
                'revenueChange' => $revenueChange
            ];
        });

        // --- PREPARE API RESPONSE ---
        return response()->json([
            'data' => [
                'overview' => [
                    'users' => $totalUsers,
                    'companies' => $totalCompanies,
                    'products' => $totalProducts,
                    'orders' => $totalOrders,
                ],
                'subscriptions' => [
                    'distribution' => $subscriptionDistribution,
                    'total' => $subscriptionDistribution->sum(),
                ],
                'bestPerformingCompanies' => $bestPerformingCompanies,
            ]
        ]);
    }
}
