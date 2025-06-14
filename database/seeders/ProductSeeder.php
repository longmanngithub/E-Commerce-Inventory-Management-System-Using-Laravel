<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryId = 1;
        $companyId = 11;

        $sampleNames = [
            'Wireless Mouse', 'Mechanical Keyboard', 'Gaming Monitor', 'USB Hub', 'Laptop Stand',
            'Webcam', 'Bluetooth Speaker', 'External Hard Drive', 'Gaming Chair', 'Microphone',
            'Smartphone', 'Tablet', 'Laptop Charger', 'HDMI Cable', 'Graphic Tablet',
            'Wireless Earbuds', 'WiFi Router', 'Power Bank', 'Portable SSD', 'Smartwatch'
        ];

        for ($i = 1; $i <= 50; $i++) {
            $name = $sampleNames[array_rand($sampleNames)] . " " . $i;
            $price = rand(500, 20000) / 1.0; // Random price between 500 and 20000
            $sku = strtoupper(Str::random(3)) . '-' . rand(1000, 9999);

            $product = Product::create([
                'product_name' => $name,
                'product_SKU' => $sku,
                'product_expiry_date' => Carbon::now()->addMonths(rand(6, 36))->toDateString(),
                'product_price' => $price,
                'product_desc' => 'Auto-generated product description for ' . $name,
                'product_image' => 'products/default.jpg',
                'category_id' => $categoryId,
                'company_id' => $companyId,
            ]);

            Stock::create([
                'product_id' => $product->product_id,
                'stock_quantity' => rand(0, 100),
                'purchase_price' => $price * (rand(50, 80) / 100), // 50–80% of sale price
                'stock_purchase_date' => Carbon::now()->subDays(rand(1, 90)),
                'company_id' => $companyId,
            ]);
        }
    }
}
