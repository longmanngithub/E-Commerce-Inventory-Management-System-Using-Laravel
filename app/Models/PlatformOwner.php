<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Use Authenticatable for login

class PlatformOwner extends Authenticatable
{
    use HasFactory;

    protected $table = 'platform_owner';
    protected $primaryKey = 'owner_id';
    public $timestamps = false; // Your table doesn't have created_at/updated_at

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
}
