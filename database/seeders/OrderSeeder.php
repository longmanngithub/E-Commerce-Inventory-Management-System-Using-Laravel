<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        if ($companies->isEmpty()) {
            $this->command->info('No companies found, skipping order creation.');
            return;
        }

        foreach ($companies as $company) {
            $this->command->info("Seeding orders for company: {$company->company_name}...");

            $products = Product::where('company_id', $company->company_id)->get();
            if ($products->count() < 2) {
                $this->command->info("Not enough products for {$company->company_name}, skipping.");
                continue;
            }

            $customers = Customer::factory(5)->create();

            for ($i = 0; $i < 10; $i++) {
                $orderStatus = ['Paid', 'Canceled'][array_rand(['Paid', 'Canceled'])];

                // Create the main order record with all its data
                $order = Order::create([
                    'company_id' => $company->company_id,
                    'customer_id' => $customers->random()->customer_id,
                    'order_date' => now()->subDays(rand(1, 30)),
                    'order_status' => $orderStatus,
                    'total_amount' => 0, // We'll calculate this in a moment
                ]);

                $totalAmount = 0;
                $itemsInOrder = rand(1, 3);

                for ($j = 0; $j < $itemsInOrder; $j++) {
                    $product = $products->random();
                    $quantity = rand(1, 5);
                    $unitPrice = $product->product_price;

                    // Create the line item record with all its data
                    OrderItem::create([
                        'order_id' => $order->order_id,
                        'product_id' => $product->product_id,
                        'order_item_quantity' => $quantity,
                        'order_item_unit_price' => $unitPrice,
                    ]);

                    $totalAmount += $quantity * $unitPrice;

                    // If the order is 'Paid', deduct stock
                    if ($orderStatus === 'Paid') {
                        $stock = Stock::where('product_id', $product->product_id)->first();
                        if ($stock) {
                            $stock->decrement('stock_quantity', $quantity);
                        }
                    }
                }

                // Update the order with the correct calculated total
                $order->total_amount = $totalAmount;
                $order->save();
            }
        }
        $this->command->info('Sample orders have been created successfully!');
    }
}
