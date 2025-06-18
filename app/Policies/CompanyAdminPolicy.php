<?php

namespace App\Policies;

use App\Models\CompanyAdmin;
use Illuminate\Auth\Access\Response;

class CompanyAdminPolicy
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
    public function view(CompanyAdmin $user, CompanyAdmin $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(CompanyAdmin $companyAdmin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(CompanyAdmin $user, CompanyAdmin $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(CompanyAdmin $user, CompanyAdmin $model): bool
    {
        // Rule 1: An admin can only be deleted by another admin from the same company.
        if ($user->company_id !== $model->company_id) {
            return false;
        }
        // Rule 2: The company owner cannot be deleted.
        return !$model->is_owner;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(CompanyAdmin $user, CompanyAdmin $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(CompanyAdmin $user, CompanyAdmin $model): bool
    {
        return false;
    }
}
