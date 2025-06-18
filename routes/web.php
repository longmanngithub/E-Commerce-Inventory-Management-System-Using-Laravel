<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductHistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PlatformOwnerLoginController;
use App\Http\Controllers\Auth\CompanyAdminLoginController;
use App\Http\Controllers\Auth\CompanyAdminRegisterController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\Auth\CompanyAdminPasswordResetController;
use App\Http\Controllers\Auth\PlatformOwnerPasswordResetController;

// --- HOMEPAGE ---
Route::get('/', function () {
    return view('welcome');
});

// --- INVITATION ACCEPTANCE (PUBLIC) ---
Route::get('/invitation/accept/{token}', [InvitationController::class, 'accept'])->name('invitation.accept');
Route::post('/invitation/set-password', [InvitationController::class, 'storePassword'])->name('invitation.store_password');


// --- COMPANY USER PROTECTED ROUTES ---
Route::middleware(['auth:company_admin,company_staff', 'check.company.status'])->group(function () {

    Route::middleware('subscribed')->group(function() {

        // Dashboard view
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile settings
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('admin.password.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');

        // Products view
        Route::resource('products', ProductController::class);
        Route::post('/products/bulk-delete', [ProductController::class, 'bulkDestroy'])->name('products.bulkDestroy');
        Route::get('/products/{productId}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');

        // Orders view
        Route::resource('orders', OrderController::class);
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('/orders/{orderId}/export', [OrderController::class, 'exportCsv'])->name('orders.export');

        Route::get('/analytics-report', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics-report/export', [AnalyticsController::class, 'exportCsv'])->name('analytics.export');
    });

    // Subscription view (user is logged in but might not be subscribed)
    Route::get('/subscription/plans', [SubscriptionController::class, 'index'])->name('subscription.plans');
    Route::get('/subscription/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::post('/subscription/store', [SubscriptionController::class, 'storeSubscription'])->name('subscription.store');
    Route::post('/subscription/change', [SubscriptionController::class, 'changePlan'])->name('subscription.change');
});


// --- ADMIN-ONLY MANAGEMENT ROUTES ---
Route::middleware(['auth:company_admin', 'subscribed', 'check.company.status'])->prefix('management')->name('management.')->group(function () {

    // Company view
    Route::get('/company', [CompanyController::class, 'edit'])->name('company.edit');
    Route::put('/company', [CompanyController::class, 'update'])->name('company.update');
    Route::post('/company/deactivate', [CompanyController::class, 'deactivate'])->name('company.deactivate');

    // Users view
    Route::get('/users', [CompanyUserController::class, 'index'])->name('users.index');
    Route::get('/users/invite', [CompanyUserController::class, 'create'])->name('users.create');
    Route::post('/users/invite', [CompanyUserController::class, 'store'])->name('users.store');

    // Staff Edit/Delete
    Route::put('/users/staff/{userId}', [CompanyUserController::class, 'updateStaff'])->name('users.update.staff');
    Route::delete('/users/staff/{userId}', [CompanyUserController::class, 'destroyStaff'])->name('users.destroy.staff');

    // Admin Delete
    Route::delete('/users/admin/{userId}', [CompanyUserController::class, 'destroyAdmin'])->name('users.destroy.admin');

    // Logs view
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
});


require __DIR__.'/auth.php';
