<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * These should match the columns in your table.
     * @var array
     */
    protected $fillable = [
        'product_name',
        'product_SKU',
        'product_expiry_date',
        'product_price',
        'product_desc',
        'product_image',
        'category_id',
        'company_id',
    ];

    // A product belongs to one company
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    // A product belongs to one category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // A product has many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // A product has one stock record
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_id');
    }

    /**
     * Get the product's stock status.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function stockStatus(): Attribute
    {
        // First, we define our thresholds. Let's say "low stock" is 10 items or less.
        $lowStockThreshold = 10;

        // We get the quantity from the related stock record.
        // We use the '?' nullsafe operator in case a product has no stock record yet.
        $quantity = $this->stocks->sum('stock_quantity');

        return Attribute::make(
            get: function () use ($quantity, $lowStockThreshold) {
                if ($quantity === null || $quantity <= 0) {
                    return 'Out of Stock';
                }
                if ($quantity <= $lowStockThreshold) {
                    return 'Low Stock';
                }
                return 'In Stock';
            },
        );
    }
}
