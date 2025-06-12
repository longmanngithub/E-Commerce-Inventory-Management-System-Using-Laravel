<?php

namespace App\Http\Controllers;

use App\Mail\UserInvitationMail;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use Illuminate\Http\Request;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

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

    /**
     * Create user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function create()
    {
        // Add authorization: only admins can create users
        if (! Auth::guard('company_admin')->check()) {
            abort(403, 'Only company admins can add new users.');
        }

        return view('users.create');
    }

    /**
     * Store user info
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (! Auth::guard('company_admin')->check()) { abort(403); }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',
                            Rule::unique('company_admin', 'admin_email'), // Must not exist in the admin table
                            Rule::unique('company_staff', 'staff_email'), // Must not exist in the staff table
                        ],
            'role' => ['required', 'string', 'in:admin,staff'],
            'permissions' => 'nullable|array',
        ]);

        // Generate a secure, random token
        $token = \Illuminate\Support\Str::uuid()->toString();

        // Store the invitation in our new table
        $invitation = UserInvitation::create([
            'company_id' => Auth::user()->company_id,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'token' => $token,
            'permissions' => $request->role === 'staff' ? $request->input('permissions', []) : null,
        ]);

        // Send an email to the user with the special link
        Mail::to($request->email)->send(new \App\Mail\UserInvitationMail($invitation));

        return redirect()->route('management.users.index')->with('status', 'Invitation sent successfully!');
    }

    /**
     * Edit staff permissions
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function editStaff($id)
    {
        $staff = CompanyStaff::where('staff_id', $id)
            ->where('company_id', Auth::user()->company_id)
            ->firstOrFail();

        return view('users.edit', ['user' => $staff]);
    }

    /**
     * Update staff permissions
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStaff(Request $request, $id)
    {
        $staff = CompanyStaff::where('staff_id', $id)
            ->where('company_id', Auth::user()->company_id)
            ->firstOrFail();

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:create_product,update_product,delete_product',
        ]);

        $staff->permissions = $request->input('permissions', []);
        $staff->save();

        return redirect()->route('management.users.index')->with('status', 'Staff permissions updated successfully!');
    }

    /**
     * Delete staff account
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyStaff($id)
    {
        $staff = CompanyStaff::where('staff_id', $id)
            ->where('company_id', Auth::user()->company_id)
            ->firstOrFail();
        $staff->delete();
        return redirect()->route('management.users.index')->with('status', 'Staff user has been deleted.');
    }

    /**
     * Delete admin account
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyAdmin($id)
    {
        $adminToDelete = CompanyAdmin::where('admin_id', $id)
            ->where('company_id', Auth::user()->company_id)
            ->firstOrFail();

        if ($adminToDelete->is_owner) {
            return redirect()->route('management.users.index')->with('error', 'You cannot delete the primary company owner.');
        }

        if ($adminToDelete->admin_id === Auth::user()->admin_id) {
            return redirect()->route('management.users.index')->with('error', 'You cannot delete your own account.');
        }

        $adminToDelete->delete();
        return redirect()->route('management.users.index')->with('status', 'Admin user has been deleted.');
    }
}
