<?php

use App\Http\Controllers\Api\AdminCompanyController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CompanyAnalyticsController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CompanyUserController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Models\CompanyAdmin;
use App\Models\CompanyStaff;
use App\Models\PlatformOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

//Route::bind('user', function ($value) {
//    // Sequentially check each user table for the given ID.
//    return PlatformOwner::find($value)
//        ?? CompanyAdmin::find($value)
//        ?? CompanyStaff::find($value);
//});

// --- PUBLIC ROUTES ---

// Login
Route::post('/auth/login', [AuthController::class, 'login']);

// Register
Route::post('/auth/register', [AuthController::class, 'register']);

// Forgot password
Route::post('/forgot-password', [PasswordResetController::class, 'sendCode']);
Route::post('/verify-code', [PasswordResetController::class, 'verifyCode']);
Route::post('/reset-password', [PasswordResetController::class, 'updatePassword']);

// Subscription plans
Route::get('/plans', [PlanController::class, 'index']);

// User invitations
Route::get('/invitations/{token}', [InvitationController::class, 'show']);
Route::post('/invitations/complete', [InvitationController::class, 'complete']);

// Profile picture
Route::get('/users/{userType}/{userId}/photo', [\App\Http\Controllers\Api\ProfileController::class, 'showPhoto'])->name('api.users.photo');


// Routes that require an API token to access
Route::middleware(['auth:sanctum'])->group(function () {

    // UNIVERSAL logout
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // UNIVERSAL profile settings
    Route::get('/user', [ProfileController::class, 'show']);
    Route::post('/user/profile-information', [ProfileController::class, 'update']);
    Route::put('/user/password', [ProfileController::class, 'updatePassword']);
    Route::delete('/user', [ProfileController::class, 'destroy']);
    Route::post('/user/photo', [ProfileController::class, 'updatePhoto']);

    // Subscription
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);

    // --- COMPANY API ROUTES ---
    Route::middleware('company.valid')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Products
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::apiResource('products', ProductController::class);
        Route::patch('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus']);
        Route::get('/products/{product}/purchases', [ProductController::class, 'getPurchaseHistory']);
        Route::post('/products/bulk-delete', [ProductController::class, 'bulkDestroy']);

        // Orders
        Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
        Route::get('/orders/{order}/export-csv', [OrderController::class, 'exportSingleOrderCsv'])->name('api.orders.exportSingle');

        // Users
        Route::get('/users', [CompanyUserController::class, 'index']);
        Route::post('/users/invite', [CompanyUserController::class, 'invite']);
        Route::put('/users/staff/{staff}', [CompanyUserController::class, 'updateStaffPermissions']);
        Route::delete('/users/staff/{staff}', [CompanyUserController::class, 'destroyStaff']);
        Route::delete('/users/admin/{admin}', [CompanyUserController::class, 'destroyAdmin']);

        // Company
        Route::get('/company', [AdminCompanyController::class, 'show']);
        Route::put('/company', [AdminCompanyController::class, 'update']);
        Route::post('/company/deactivate', [AdminCompanyController::class, 'deactivate']);

        // Logs
        Route::get('/logs', [LogController::class, 'index']);

        // Analytics
        Route::get('/analytics-report', [CompanyAnalyticsController::class, 'index']);
        Route::get('/analytics-report/export', [CompanyAnalyticsController::class, 'exportCsv']);

        // Notification
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{notificationId}/mark-as-read', [NotificationController::class, 'markAsRead']);
    });


    // --- PLATFORM OWNER API ROUTES ---

    // Platform owner view company route
    Route::get('/companies', [CompanyController::class, 'index']);
    Route::get('/companies/{company}', [CompanyController::class, 'show']);

    // Reactivate company
    Route::post('/companies/{company}/reactivate', [CompanyController::class, 'reactivate'])->name('api.companies.reactivate');

    // Platform owner analytics report
    Route::get('/analytics', [AnalyticsController::class, 'index']);
});
