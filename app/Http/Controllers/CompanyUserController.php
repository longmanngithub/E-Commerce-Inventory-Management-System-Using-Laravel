<?php

namespace App\Http\Controllers;

use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use Illuminate\Http\Request;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitationMail;

class CompanyUserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Get the company ID from whichever user is logged in (admin or staff).
        // Note: Auth::user() will automatically resolve to the correct guard for the logged-in user.
        $companyId = Auth::user()->company_id;

        // Get all admins and staff for that specific company
        $admins = CompanyAdmin::where('company_id', $companyId)->get();
        $staff = CompanyStaff::where('company_id', $companyId)->get();

        // Merge the two lists into a single collection to display in the view
        $users = $admins->concat($staff);

        // Send the collection of users to our view
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Add authorization: only admins can create users
        if (! Auth::guard('company_admin')->check()) {
            abort(403, 'Only company admins can add new users.');
        }

        return view('users.create');
    }

    public function store(Request $request)
    {
        if (! Auth::guard('company_admin')->check()) { abort(403); }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', 'string', 'in:admin,staff'], // A new field to select the role
        ]);

        // Generate a secure, random token
        $token = Str::uuid()->toString();

        // Store the invitation in our new table
        $invitation = UserInvitation::create([
            'company_id' => Auth::user()->company_id,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'token' => $token
        ]);

        // Send an email to the user with the special link
        Mail::to($request->email)->send(new UserInvitationMail($invitation));

        return redirect()->route('admin.users.index')->with('status', 'Invitation sent successfully!');
    }
}
