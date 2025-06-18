<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

// Use Authenticatable for login

class PlatformOwner extends Authenticatable
{
    use HasApiTokens;
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
     * Return password
     *
     * @return string
     */
    public function getAuthPasswordName()
    {
        return 'owner_password';
    }

    /**
     * Get user type
     *
     * @return string
     */
    public function getAuthGuard() {
        return 'platform_owner';
    }
}
