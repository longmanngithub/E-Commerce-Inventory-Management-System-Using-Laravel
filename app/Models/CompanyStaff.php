<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Use Authenticatable for login

class CompanyStaff extends Authenticatable
{
    use HasFactory;

    protected $table = 'company_staff';
    protected $primaryKey = 'staff_id';
    public $timestamps = false;

    protected $fillable = [
        'staff_name',
        'staff_email',
        'staff_password',
        'staff_image',
        'company_id',
    ];

    protected $hidden = [
        'staff_password',
    ];

    /**
     * Get the company that the staff member belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the audit logs for the staff member.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'staff_id');
    }
}
