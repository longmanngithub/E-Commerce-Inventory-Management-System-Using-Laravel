<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_log';
    protected $primaryKey = 'log_id';
    public $timestamps = false; // The table has a manual 'timestamp' column

    protected $fillable = [
        'staff_id',
        'action',
        'timestamp',
        'entity_affected',
        'details',
    ];

    /**
     * Get the staff member who performed the action.
     */
    public function staff()
    {
        return $this->belongsTo(CompanyStaff::class, 'staff_id');
    }

    /**
     * Get the parent user model (can be a CompanyAdmin or CompanyStaff).
     */
    public function user()
    {
        return $this->morphTo();
    }

    public function subject()
    {
        return $this->morphTo();
    }
}
