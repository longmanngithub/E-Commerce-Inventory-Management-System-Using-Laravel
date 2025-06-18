<?php
namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogService
{
    /**
     * Create a new audit log entry.
     */
    public function log(Request $request, string $action, string $details, $subject = null)
    {
        $user = $request->user();

        $log = new AuditLog([
            'action' => $action,
            'details' => $details,
            'entity_affected' => $subject ? class_basename($subject) : null,
            'timestamp' => now(),
        ]);

        // Associate the log with the user who performed the action.
        if ($user) {
            $log->user()->associate($user);
        }

        // Associate the log with the model that was changed, if provided.
        if ($subject) {
            $log->subject()->associate($subject);
        }

        $log->save();
    }
}
