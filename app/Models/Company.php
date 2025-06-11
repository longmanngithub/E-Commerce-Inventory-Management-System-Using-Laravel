<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';
    protected $primaryKey = 'company_id';
    public $timestamps = false;

    protected $fillable = [
        'company_name',
        'company_address',
        'company_email',
        'company_telephone',
        'company_image',
        'company_desc',
        'register_date',
    ];

    // A company has many products
    public function products()
    {
        return $this->hasMany(Product::class, 'company_id');
    }

    // A company has many staff members
    public function staff()
    {
        return $this->hasMany(CompanyStaff::class, 'company_id');
    }

    // A company has many admins
    public function admins()
    {
        return $this->hasMany(CompanyAdmin::class, 'company_id');
    }

    // A company has many orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'company_id');
    }

    // A company has many categories
    public function categories()
    {
        return $this->hasMany(Category::class, 'company_id');
    }

    // A Company has one Subscription Order
    public function subscription()
    {
        return $this->hasOne(SubscriptionOrder::class, 'company_id');
    }
}
