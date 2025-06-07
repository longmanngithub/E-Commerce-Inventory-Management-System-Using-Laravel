<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'product_id';
    public $timestamps = false;

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
    public function stock()
    {
        return $this->hasOne(Stock::class, 'product_id');
    }
}
