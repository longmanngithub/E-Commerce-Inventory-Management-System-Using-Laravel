<?php

namespace App\Models;

use App\Notifications\CompanyPasswordResetNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Use Authenticatable for login
use Illuminate\Notifications\Notifiable;

class CompanyStaff extends Authenticatable
{
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

    public function getAuthPassword()
    {
        return $this->staff_password;
    }

    /**
     * Get the company that the staff member belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CompanyPasswordResetNotification($token));
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
}
