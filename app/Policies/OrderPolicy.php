<?php

namespace App\Policies;

use App\Models\CompanyAdmin;
use App\Models\Order;
use Illuminate\Auth\Access\Response;

class OrderPolicy
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
    public function view($user, Order $order): bool
    {
        // A user can view an order if it belongs to their company.
        return $user->company_id === $order->company_id;
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
    public function update($user, Order $order): bool
    {
        // A user can update/cancel an order if it belongs to their company.
        return $user->company_id === $order->company_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(CompanyAdmin $companyAdmin, Order $order): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(CompanyAdmin $companyAdmin, Order $order): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(CompanyAdmin $companyAdmin, Order $order): bool
    {
        return false;
    }
}
