<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // --- Our Custom Permission Gates ---

        // A user can create a product if...
        Gate::define('create-product', function ($user) {
            // 1. They are a company admin.
            if ($user instanceof \App\Models\CompanyAdmin) {
                return true;
            }
            // 2. They are a staff member who has the 'create_product' permission.
            if ($user instanceof \App\Models\CompanyStaff) {
                return in_array('create_product', $user->permissions ?? []);
            }
            return false;
        });

        // A user can update a product if...
        Gate::define('update-product', function ($user, Product $product) {

            // 1. They are an admin of the product's company.
            if ($user instanceof CompanyAdmin && $user->company_id === $product->company_id) {
                return true;
            }
            // 2. They are a staff member who has the 'update_product' permission.
            if ($user instanceof CompanyStaff && $user->company_id === $product->company_id) {
                return in_array('update_product', $user->permissions ?? []);
            }
            return false;
        });

        // A user can delete a product if...
        Gate::define('delete-product', function ($user, Product $product) {
            // 1. They are an admin of the product's company.
            if ($user instanceof CompanyAdmin && $user->company_id === $product->company_id) {
                return true;
            }
            // 2. They are a staff member who has the 'delete_product' permission.
            if ($user instanceof CompanyStaff && $user->company_id === $product->company_id) {
                return in_array('delete_product', $user->permissions ?? []);
            }
            return false;
        });

        Gate::define('bulk-delete-products', function ($user) {
            // Only an admin can bulk delete
            if ($user instanceof \App\Models\CompanyAdmin) {
                return true;
            }
            // Or a staff member with the specific 'delete_product' permission
            if ($user instanceof \App\Models\CompanyStaff) {
                return in_array('delete_product', $user->permissions ?? []);
            }
            return false;
        });
    }
}
