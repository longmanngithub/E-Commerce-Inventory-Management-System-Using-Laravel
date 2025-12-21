<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use App\Notifications\LowStockWarning;
use Illuminate\Support\Facades\Notification;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyId = 1; // Target a specific company
        $company = Company::find($companyId);
        if (!$company) {
            $this->command->error("Company with ID {$companyId} not found, skipping order creation.");
            return;
        }

        $products = Product::where('company_id', $company->company_id)->get();
        if ($products->count() < 2) {
            $this->command->info("Not enough products for {$company->company_name}, skipping.");
            return;
        }

        $customers = Customer::factory(25)->create();
        $adminsToNotify = $company->admins;
        $staffToNotify = $company->staff;
        $allUsersToNotify = $adminsToNotify->concat($staffToNotify);

        $this->command->info("Seeding orders for company: {$company->company_name}...");

        for ($i = 0; $i < 50; $i++) {
            // Define the status *before* you use it.
            $orderStatus = ['Paid', 'Canceled'][array_rand(['Paid', 'Canceled'])];

            $order = Order::create([
                'company_id' => $company->company_id,
                'customer_id' => $customers->random()->customer_id,
                'order_date' => now()->subDays(rand(1, 30)),
                'order_status' => $orderStatus,
                'total_amount' => 0,
            ]);

            $totalAmount = 0;
            $itemsInOrder = rand(1, 3);

            for ($j = 0; $j < $itemsInOrder; $j++) {
                $product = $products->random();
                $quantity = rand(1, 5);
                // Get the unit price from the product object.
                $unitPrice = $product->product_price;

                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product->product_id,
                    'order_item_quantity' => $quantity,
                    'order_item_unit_price' => $unitPrice,
                ]);

                $totalAmount += $quantity * $unitPrice;

                if ($orderStatus === 'Paid') {
                    $stock = Stock::where('product_id', $product->product_id)->first();
                    if ($stock) {
                        $stock->decrement('stock_quantity', $quantity);
                    }
                }
            }

            $order->update(['total_amount' => $totalAmount]);

            // Notification check logic...
            foreach ($order->orderItems as $item) {
                $product = $item->product->fresh();
                $currentStock = $product->stocks()->sum('stock_quantity');
                if ($currentStock <= $product->reorder_point && $currentStock > 0) {
                    Notification::send($allUsersToNotify, new LowStockWarning($product));
                }
            }
        }

        $this->command->info("Sample orders have been created successfully for {$company->company_name}!");
    }
}
