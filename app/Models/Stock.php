<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';
    protected $primaryKey = 'stock_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'stock_quantity',
        'stock_purchase_date',
        'company_id',
    ];

    /**
     * Get the product that this stock record belongs to.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the company that this stock belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
