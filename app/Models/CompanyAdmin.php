<?php

namespace App\Models;

use App\Notifications\CompanyPasswordResetNotification;
use Illuminate\Foundation\Auth\User as Authenticatable; // Use Authenticatable for login
use Illuminate\Notifications\Notifiable;

class CompanyAdmin extends Authenticatable
{
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
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'admin_password',
    ];

    /**
     * Override the password column for authentication.
     */
    public function getAuthPassword()
    {
        return $this->admin_password;
    }

    /**
     * Get the company that the admin belongs to.
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
        return $this->admin_email;
    }
}
