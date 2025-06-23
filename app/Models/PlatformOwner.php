<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Use Authenticatable for login

class PlatformOwner extends Authenticatable
{
    use HasFactory;

    protected $table = 'platform_owner';
    protected $primaryKey = 'owner_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_name',
        'owner_email',
        'owner_password',
        'owner_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'owner_password',
    ];

    /**
     * Override the default password column name.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->owner_password;
    }

    /**
     * Get user type
     *
     * @return string
     */
    public function getAuthGuard() {
        return 'platform_owner';
    }

    /**
     * Get the user's role.
     */
    public function getRoleAttribute(): string
    {
        return 'Platform Owner';
    }

    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function getNameAttribute()
    {
        return $this->owner_name;
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
