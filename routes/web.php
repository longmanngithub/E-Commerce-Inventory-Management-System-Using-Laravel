<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyUserController;
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

// --- PLATFORM OWNER ROUTES ---
Route::prefix('owner')->name('owner.')->group(function(){
    // Login
    Route::get('/login', [PlatformOwnerLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PlatformOwnerLoginController::class, 'login'])->name('login.attempt');

    // Forgot Password
    Route::get('/forgot-password', [PlatformOwnerPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PlatformOwnerPasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PlatformOwnerPasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PlatformOwnerPasswordResetController::class, 'reset'])->name('password.update');

    // Protected Dashboard
    Route::middleware('auth:platform_owner')->group(function () {

        // Logout
        Route::post('/logout', [PlatformOwnerLoginController::class, 'logout'])->name('logout');

        // Dashboard view
        Route::get('/dashboard', fn() => 'Welcome Platform Owner!')->name('dashboard');

        // Profile settings
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});


// --- COMPANY USER PROTECTED ROUTES ---
Route::middleware(['auth:company_admin,company_staff', 'check.company.status'])->group(function () {

    // Dashboard view
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::middleware('subscribed')->group(function() {

        // Profile settings
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('admin.password.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');

        // Products view
        Route::resource('products', ProductController::class);
        Route::delete('/products', [ProductController::class, 'bulkDestroy'])->name('products.bulkDestroy');
        Route::get('/product-history/{productName}', [ProductHistoryController::class, 'show'])->name('products.history');

        // Orders view
        Route::resource('orders', OrderController::class);
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('/orders/{order}/export', [OrderController::class, 'exportCsv'])->name('orders.export');

        Route::get('/analytics-report', [AnalyticsController::class, 'index'])->name('analytics.index');
    });

    // Subscription view
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
    Route::get('/users/create', [CompanyUserController::class, 'create'])->name('users.create');
    Route::post('/users', [CompanyUserController::class, 'store'])->name('users.store');

    // Staff Edit/Delete
    Route::get('/staff/{id}/edit', [CompanyUserController::class, 'editStaff'])->name('users.edit.staff');
    Route::put('/staff/{id}', [CompanyUserController::class, 'updateStaff'])->name('users.update.staff');
    Route::delete('/staff/{id}', [CompanyUserController::class, 'destroyStaff'])->name('users.destroy.staff');

    // Admin Delete
    Route::delete('/admins/{id}', [CompanyUserController::class, 'destroyAdmin'])->name('users.destroy.admin');
});


require __DIR__.'/auth.php';
