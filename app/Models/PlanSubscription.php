<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanSubscription extends Model
{
    use HasFactory;

    protected $table = 'plan_subscription';
    protected $primaryKey = 'subscription_id';
    public $timestamps = false;

    protected $fillable = [
        'subscription_tier',
        'subscription_price',
        'product_limit',
        'monthly',
    ];
}
