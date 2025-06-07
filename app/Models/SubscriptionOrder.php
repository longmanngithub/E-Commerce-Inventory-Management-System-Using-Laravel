<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionOrder extends Model
{
    use HasFactory;

    protected $table = 'subscription_order';
    protected $primaryKey = 'subscription_order_id';
    // This table uses Laravel's timestamps by default (start_date)
    // but we can manage them manually if needed.
    public $timestamps = false;

    protected $fillable = [
        'subscription_tier',
        'subscription_price',
        'is_paid',
        'monthly',
        'renew_date',
        'start_date',
        'end_date',
        'company_id',
        'company_staff_id',
        'company_admin_id',
    ];

    /**
     * Get the company that this subscription belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the admin who initiated this subscription.
     */
    public function companyAdmin()
    {
        return $this->belongsTo(CompanyAdmin::class, 'company_admin_id');
    }

    /**
     * Get the staff who initiated this subscription.
     */
    public function companyStaff()
    {
        return $this->belongsTo(CompanyStaff::class, 'company_staff_id');
    }
}
