<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

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
        'status',
        'product_SKU',
        'reorder_point',
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
        return Attribute::make(
            get: function () {
                // Get the total stock quantity for this product.
                $quantity = $this->stocks->sum('stock_quantity');

                // Get this specific product's reorder point.
                // We use '?? 10' as a safe fallback in case it's null.
                $reorderPoint = $this->reorder_point ?? 10;

                if ($quantity <= 0) {
                    return 'Out of Stock';
                }

                // Compare the quantity against the product's own reorder point.
                if ($quantity <= $reorderPoint) {
                    return 'Low Stock';
                }

                return 'In Stock';
            },
        );
    }

    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'subject');
    }
}
