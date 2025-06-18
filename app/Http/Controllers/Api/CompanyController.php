<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Get all the companies
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $companies = Company::with('subscription')->get();

        // This will format the entire collection using our resource
        return CompanyResource::collection($companies);
    }

    /**
     * Show company overview
     *
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Company $company)
    {
        // Eager load relationships for efficiency
        $company->load('subscription', 'products', 'admins', 'staff', 'orders');

        // --- CALCULATE OVERVIEW STATS ---
        $allProductsCount = $company->products->count();

        // Calculate Revenue and Sales for the last 30 days
        $revenueThisMonth = $company->orders()
            ->where('order_status', 'Paid')
            ->whereBetween('order_date', [now()->subDays(30), now()])
            ->sum('total_amount');

        $revenueLastMonth = $company->orders()
            ->where('order_status', 'Paid')
            ->whereBetween('order_date', [now()->subDays(60), now()->subDays(30)])
            ->sum('total_amount');

        // Calculate percentage change, avoiding division by zero
        $revenueChangePercentage = 0;
        if ($revenueLastMonth > 0) {
            $revenueChangePercentage = (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100;
        }

        // --- GET BEST SELLING PRODUCTS ---
        $bestSellingProducts = OrderItem::whereIn('order_id', $company->orders->pluck('order_id'))
            ->join('product', 'order_item.product_id', '=', 'product.product_id')
            ->join('category', 'product.category_id', '=', 'category.category_id')
            ->select(
                'product.product_name',
                'category.category_name',
                DB::raw('SUM(order_item.order_item_quantity * order_item.order_item_unit_price) as revenue')
            )
            ->groupBy('product.product_id', 'product.product_name', 'category.category_name')
            ->orderBy('revenue', 'desc')
            ->limit(3)
            ->get();

        // --- PREPARE API RESPONSE ---
        $data = (new \App\Http\Resources\CompanyResource($company))->toArray(request());
        $data['analytics'] = [
            'allProducts' => $allProductsCount,
            'revenue' => $revenueThisMonth,
            'revenueChange' => $revenueChangePercentage,
            'sales' => $revenueThisMonth, // Assuming Sales are the same as Revenue
            'salesChange' => $revenueChangePercentage,
        ];
        $data['companyDetails'] = [
            'name' => $company->company_name,
            'address' => $company->company_address,
            'email' => $company->company_email,
            'nextBilling' => optional($company->subscription)->renew_date,
            'memberSince' => $company->register_date,
        ];
        $data['bestSellingProducts'] = $bestSellingProducts;

        return response()->json(['data' => $data]);
    }

    /**
     * Reactivate a company's account and subscription.
     */
    public function reactivate(Company $company)
    {
        // Because of Route-Model Binding, Laravel automatically finds the company from the ID in the URL.

        // Update the company status
        $company->status = 'Active';
        $company->save();

        // Also, reactivate their subscription if it exists
        if ($company->subscription) {
            $company->subscription->update([
                'is_paid' => true,
                'renew_date' => now()->addMonth(), // Set a new renewal date
            ]);
        }

        return response()->json(['message' => "Company '{$company->company_name}' has been reactivated."]);
    }
}
