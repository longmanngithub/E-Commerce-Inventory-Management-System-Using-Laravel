<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Electronics', 'category_desc' => 'Gadgets and electronic devices.'],
            ['category_name' => 'Fashion & Beauty', 'category_desc' => 'Clothing, accessories, and beauty products.'],
            ['category_name' => 'Food & Beverage', 'category_desc' => 'Groceries, snacks, and drinks.'],
            ['category_name' => 'Health & Personal Care', 'category_desc' => 'Vitamins, supplements, and personal hygiene products.'],
            ['category_name' => 'Home Decor', 'category_desc' => 'Items for decorating your home.'],
            ['category_name' => 'Industrial', 'category_desc' => 'Industrial equipment and supplies.'],
            ['category_name' => 'Sports & Entertainment', 'category_desc' => 'Sporting goods and entertainment products.'],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['category_name' => $categoryData['category_name']],
                ['category_desc' => $categoryData['category_desc']]
            );
        }
    }
}
