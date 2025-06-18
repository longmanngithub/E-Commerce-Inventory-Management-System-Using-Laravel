<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Use Authenticatable for login
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CompanyStaff extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $table = 'company_staff';
    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'staff_name',
        'staff_email',
        'staff_password',
        'permissions',
        'staff_image',
        'company_id',
    ];

    protected $hidden = [
        'staff_password',
    ];

    protected $guard_name = 'company_staff';

    public function getAuthPassword()
    {
        return $this->staff_password;
    }

    /**
     * Return password
     *
     * @return string
     */
    public function getAuthPasswordName()
    {
        return 'staff_password';
    }

    /**
     * Get user type
     *
     * @return string
     */
    public function getAuthGuard() {
        return 'company_staff';
    }

    /**
     * Get the company that the staff member belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->staff_email;
    }

    /**
     * The attributes that should be cast.
     * This automatically converts the JSON column to and from an array.
     * @var array
     */
    protected $casts = [
        'permissions' => 'array', // <-- ADD THIS
    ];

    /**
     * Get the audit logs for the staff member.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'staff_id');
    }

    /**
     * Get all of the staff member's audit logs.
     */
    public function logs()
    {
        return $this->morphMany(AuditLog::class, 'user');
    }

    public function getNameAttribute() {
        return $this->admin_name;
    }
}
