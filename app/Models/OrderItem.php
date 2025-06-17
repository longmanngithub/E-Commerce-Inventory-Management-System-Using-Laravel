<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_item';
    protected $primaryKey = 'order_item_id';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'order_item_quantity',
        'order_item_unit_price',
    ];

    // An order item belongs to one order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // An order item refers to one product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    /**
     * Get the profit for this individual order line item.
     */
    protected function profit(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Find the stock record associated with this product to get its purchase price.
                // Note: This is a simplified approach. A real system might need to track which batch was sold.
                $stock = Stock::where('product_id', $this->product_id)->first();
                $purchasePrice = $stock ? $stock->purchase_price : 0;

                $sellingPrice = $this->order_item_unit_price;

                // Profit per item = Selling Price - Purchase Price
                $profitPerItem = $sellingPrice - $purchasePrice;

                // Total profit for this line item = Profit per item * quantity
                return $profitPerItem * $this->order_item_quantity;
            }
        );
    }
}
