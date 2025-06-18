<?php

namespace App\Policies;

use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\Product;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user instanceof CompanyAdmin || $user instanceof CompanyStaff;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, Product $product): bool
    {
        // A user can view a product if it belongs to their company.
        return $user->company_id === $product->company_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        // An admin can always create products.
        if ($user instanceof CompanyAdmin) {
            return true;
        }
        // A staff member can create products if they have the specific permission.
        if ($user instanceof CompanyStaff) {
            return in_array('create_product', $user->permissions ?? []);
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Product $product): bool
    {
        if ($user->company_id !== $product->company_id) {
            return false;
        }
        if ($user instanceof CompanyAdmin) {
            return true;
        }
        if ($user instanceof CompanyStaff) {
            return in_array('update_product', $user->permissions ?? []);
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, Product $product): bool
    {
        if ($user->company_id !== $product->company_id) {
            return false;
        }
        if ($user instanceof CompanyAdmin) {
            return true;
        }
        if ($user instanceof CompanyStaff) {
            return in_array('delete_product', $user->permissions ?? []);
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(CompanyAdmin $companyAdmin, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(CompanyAdmin $companyAdmin, Product $product): bool
    {
        return false;
    }
}
