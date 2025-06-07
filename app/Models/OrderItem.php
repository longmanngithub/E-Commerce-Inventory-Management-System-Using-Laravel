<?php

namespace App\Models;

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
        return $this->belongsTo(Product::class, 'product_id');
    }
}
