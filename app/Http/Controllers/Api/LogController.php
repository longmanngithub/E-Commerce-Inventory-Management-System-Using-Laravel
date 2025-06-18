<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditLogResource;
use App\Models\AuditLog;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    // Inject the service via the constructor
    public function __construct(protected AuditLogService $auditLogService)
    {
    }

    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;
        $query = AuditLog::whereHasMorph('user', [
            \App\Models\CompanyAdmin::class, \App\Models\CompanyStaff::class
        ], fn($q) => $q->where('company_id', $companyId));

        // Add search/filter logic here based on your Figma design
        if ($request->filled('search')) {
            $query->where('details', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->with('user')->latest('timestamp')->paginate(15);
        return AuditLogResource::collection($logs);
    }
}
