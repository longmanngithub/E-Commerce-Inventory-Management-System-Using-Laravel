<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Use Authenticatable for login

class CompanyAdmin extends Authenticatable
{
    use HasFactory;

    protected $table = 'company_admin';
    protected $primaryKey = 'admin_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'admin_name',
        'admin_email',
        'admin_password',
        'admin_image',
        'company_id',
        'subscription_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'admin_password',
    ];

    /**
     * Get the company that the admin belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the subscription plan for the admin.
     */
    public function subscription()
    {
        return $this->belongsTo(PlanSubscription::class, 'subscription_id');
    }
}
