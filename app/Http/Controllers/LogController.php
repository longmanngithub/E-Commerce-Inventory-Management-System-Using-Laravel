<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $companyId = Auth::user()->company_id;

        // Start a query for all AuditLog entries
        $query = AuditLog::query();

        // Add a condition to only get logs where the 'user' (which can be an admin or staff)
        // belongs to the currently logged-in user's company.
        $query->whereHasMorph(
            'user', // This is the name of our polymorphic relationship
            [CompanyAdmin::class, CompanyStaff::class], // The possible user types
            function ($query) use ($companyId) {
                // This condition is applied to both the CompanyAdmin and CompanyStaff queries
                $query->where('company_id', $companyId);
            }
        );

        // If a search term is provided, filter by the 'details' column
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('details', 'LIKE', "%{$searchTerm}%");
        }

        // Filter by action types
        if ($request->filled('actions')) {
            $query->whereIn('action', $request->actions);
        }

        // --- APPLY SORTING ---
        if ($request->input('sort_by') === 'oldest') {
            $query->orderBy('timestamp', 'asc');
        } else {
            // Default to newest first
            $query->orderBy('timestamp', 'desc');
        }

        // Eager load the user data for display and sort by the newest log first
        $logs = $query->with('user')
            ->latest('timestamp')
            ->paginate(15);

        return view('logs.index', compact('logs'));
    }
}
