z<?php

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

    /**
     * Get the user's role.
     */
    public function getRoleAttribute(): string
    {
        return $this->is_owner ? 'Company Owner' : 'Admin';
    }

    /**
     * Get the URL for the user's profile picture.
     */
    public function getImageUrlAttribute(): ?string
    {
        $imageColumn = $this->getImageUrlColumn();

        if ($this->{$imageColumn}) {
            $userType = match (get_class($this)) {
                \App\Models\PlatformOwner::class => 'owner',
                \App\Models\CompanyAdmin::class => 'admin',
                \App\Models\CompanyStaff::class => 'staff',
                default => 'unknown',
            };

            return config('services.api.url') . "/users/{$userType}/{$this->getKey()}/photo";
        }

        return null;
    }

    /**
     * Get the correct column name for the profile image.
     */
    public function getImageUrlColumn(): string
    {
        // Return the specific column name for each model
        if ($this instanceof \App\Models\PlatformOwner) return 'owner_image';
        if ($this instanceof \App\Models\CompanyAdmin) return 'admin_image';
        if ($this instanceof \App\Models\CompanyStaff) return 'staff_image';
        return 'default_image_column'; // Fallback
    }
}
