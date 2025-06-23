<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInvitation extends Model
{
    use SoftDeletes;

    protected $table = 'user_invitations';
    protected $fillable = ['company_id', 'name', 'email', 'role', 'token', 'permissions'];
    public $timestamps = ['created_at']; // Only use created_at
    const UPDATED_AT = null; // Disable updated_at

    protected $casts = [
        'permissions' => 'array',
    ];
}
