<?php

namespace App\Providers;

use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\Order;
use App\Models\Product;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Order::class => OrderPolicy::class,
        \App\Models\CompanyAdmin::class => \App\Policies\CompanyAdminPolicy::class,
        \App\Models\CompanyStaff::class => \App\Policies\CompanyStaffPolicy::class,
        \App\Models\UserInvitation::class => \App\Policies\UserInvitationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('bulk-delete-products', function ($user) {
            // If the user is an admin, they can always bulk delete.
            if ($user instanceof CompanyAdmin) {
                return true;
            }
            // If the user is a staff member, check if their permissions array
            // contains the 'delete_product' permission.
            if ($user instanceof CompanyStaff) {
                return in_array('delete_product', $user->permissions ?? []);
            }
            // By default, deny.
            return false;
        });
    }
}
