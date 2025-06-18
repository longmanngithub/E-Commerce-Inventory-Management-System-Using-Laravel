<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Use Authenticatable for login
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CompanyAdmin extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;

    protected $table = 'company_admin';
    protected $primaryKey = 'admin_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'admin_name',
        'admin_email',
        'admin_password',
        'is_owner',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'admin_password',
    ];

    protected $guard_name = 'company_admin';

    protected $casts = [
        'is_owner' => 'boolean',
    ];

    /**
     * Override the password column for authentication.
     */
    public function getAuthPassword()
    {
        return $this->admin_password;
    }

    /**
     * Return password
     *
     * @return string
     */
    public function getAuthPasswordName()
    {
        return 'admin_password';
    }

    /**
     * Get user type
     *
     * @return string
     */
    public function getAuthGuard() {
        return 'company_admin';
    }

    /**
     * Get the company that the admin belongs to.
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
        return $this->admin_email;
    }

    /**
     * Get all of the admin's audit logs.
     */
    public function logs()
    {
        return $this->morphMany(AuditLog::class, 'user');
    }

    public function getNameAttribute() {
        return $this->admin_name;
    }
}
