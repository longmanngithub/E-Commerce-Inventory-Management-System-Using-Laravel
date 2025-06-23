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

    protected $guard_name = 'company_staff';

    public function getAuthPassword()
    {
        return $this->staff_password;
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

    /**
     * Get all of the staff member's audit logs.
     */
    public function logs()
    {
        return $this->morphMany(AuditLog::class, 'user');
    }

    /**
     * Get the user's role.
     */
    public function getRoleAttribute(): string
    {
        return 'Staff';
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
}
