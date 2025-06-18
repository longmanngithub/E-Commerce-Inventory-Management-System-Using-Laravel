<?php

namespace App\Policies;

use App\Models\CompanyAdmin;
use App\Models\UserInvitation;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Authenticatable;

class UserInvitationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(CompanyAdmin $companyAdmin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(CompanyAdmin $companyAdmin, UserInvitation $companyAdminInvitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Authenticatable $user): bool
    {
        // Rule: Only allow users who are an instance of CompanyAdmin to create invitations.
        return $user instanceof CompanyAdmin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(CompanyAdmin $companyAdmin, UserInvitation $companyAdminInvitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(CompanyAdmin $companyAdmin, UserInvitation $companyAdminInvitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(CompanyAdmin $companyAdmin, UserInvitation $companyAdminInvitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(CompanyAdmin $companyAdmin, UserInvitation $companyAdminInvitation): bool
    {
        return false;
    }
}
