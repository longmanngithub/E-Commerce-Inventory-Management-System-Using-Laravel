<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\UserInvitationMail;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\UserInvitation;
use App\Services\AuditLogService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompanyUserController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(protected AuditLogService $auditLogService) {}

    /**
     * List all users for the authenticated user's company.
     */
    public function index(Request $request)
    {
        $company = $request->user()->company;
        $admins = $company->admins;
        $staff = $company->staff;
        $users = $admins->concat($staff);
        return UserResource::collection($users);
    }

    /**
     * Create an invitation for a new user and send an email.
     */
    public function invite(Request $request)
    {
        // Authorize that the currently authenticated user can invite others
        $this->authorize('create', UserInvitation::class);
        $company = $request->user()->company;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                // This rule checks for uniqueness but ignores soft-deleted records
                Rule::unique('company_admin', 'admin_email')->whereNull('deleted_at'),
                Rule::unique('company_staff', 'staff_email')->whereNull('deleted_at'),
                Rule::unique('user_invitations', 'email')->whereNull('deleted_at'),
            ],
            'role' => ['required', 'in:admin,staff'],
            'permissions' => ['nullable', 'array'],
        ]);

        // Create the invitation record in the database with a unique token
        $invitation = UserInvitation::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'permissions' => $validated['permissions'] ?? [],
            'company_id' => $company->company_id,
            'token' => Str::random(32),

        ]);

        // Construct the full invitation link using the FRONTEND_URL from the .env file
        $invitationUrl = rtrim(config('app.frontend_url'), '/') . '/invitation/accept/' . $invitation->token;

        // Send the email to the invited user
        \Illuminate\Support\Facades\Mail::to($invitation->email)->send(new \App\Mail\UserInvitationMail($invitationUrl));

        $this->auditLogService->log($request, 'Invited', "Invited new user: {$validated['email']}",  $invitation);

        return response()->json(['message' => 'Invitation sent successfully.'], 201);
    }

    /**
     * Display the specified user's data (for the edit form).
     */
    public function show($userId)
    {
        // Find the user in either table within the admin's company
        $company = request()->user()->company;
        $user = $company->admins()->find($userId) ?? $company->staff()->find($userId);

        if (!$user) { abort(404, 'User not found.'); }

        $this->authorize('update', $user); // Check if the current user can update the target user

        return new UserResource($user);
    }

    /**
     * Update a staff member's permissions.
     */
    public function updateStaffPermissions(Request $request, CompanyStaff $staff)
    {
        $this->authorize('update', $staff);

        $validated = $request->validate(['permissions' => 'present|array']);

        $staff->permissions = $validated['permissions'];
        $staff->save();

        $this->auditLogService->log($request, 'Updated', "Updated permissions for staff: {$staff->staff_name}", $staff);

        return new UserResource($staff);
    }

    /**
     * Delete a company user (admin or staff).
     */
    public function destroyStaff(Request $request, CompanyStaff $staff)
    {
        $this->authorize('delete', $staff);
        $staff->delete();

        $this->auditLogService->log($request, 'Deleted', "Deleted staff: {$staff->staff_name}", $staff);

        return response()->noContent();
    }

    public function destroyAdmin(Request $request, CompanyAdmin $admin)
    {
        // Authorize that the current user is allowed to delete this admin
        $this->authorize('delete', $admin);

        // First, check if the admin to be deleted is an owner
        if ($admin->is_owner) {
            // Then, check if they are the only owner left in the company
            $ownerCount = CompanyAdmin::where('company_id', $admin->company_id)
                ->where('is_owner', true)
                ->count();

            if ($ownerCount <= 1) {
                // If they are the last owner, return a 422 error and prevent deletion
                return response()->json(['message' => 'You cannot delete the last owner of the company. Please deactivate the company instead.'], 422);
            }
        }

        // If the checks pass, proceed with deletion
        $admin->delete();

        $this->auditLogService->log($request, 'Deleted', "Deleted admin: {$admin->admin_name}", $admin);

        return response()->noContent(); // Return a "204 No Content" success response
    }
}
