<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'customer_id',
        'order_date',
        'total_amount',
        'order_status',
    ];

    // An order belongs to one company
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    // An order belongs to one customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // An order has many items
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
