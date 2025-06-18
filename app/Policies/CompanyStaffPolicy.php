<?php

namespace App\Policies;

use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use Illuminate\Auth\Access\Response;

class CompanyStaffPolicy
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
    public function view(CompanyAdmin $companyAdmin, CompanyStaff $companyStaff): bool
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
    public function update(CompanyAdmin $user, CompanyStaff $model): bool
    {
        // An admin can update a staff member in their own company.
        return $user->company_id === $model->company_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(CompanyAdmin $user, CompanyStaff $model): bool
    {
        // An admin can delete a staff member in their own company.
        return $user->company_id === $model->company_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(CompanyAdmin $companyAdmin, CompanyStaff $companyStaff): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(CompanyAdmin $companyAdmin, CompanyStaff $companyStaff): bool
    {
        return false;
    }
}
