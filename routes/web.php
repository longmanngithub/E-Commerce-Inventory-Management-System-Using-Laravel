<?php

use App\Http\Controllers\CompanyUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PlatformOwnerLoginController;
use App\Http\Controllers\Auth\CompanyAdminLoginController;
use App\Http\Controllers\Auth\CompanyAdminRegisterController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\Auth\CompanyAdminPasswordResetController;
use App\Http\Controllers\Auth\PlatformOwnerPasswordResetController;

Route::get('/', function () {
    return view('welcome');
});

// --- PLATFORM OWNER ROUTES ---
Route::prefix('owner')->name('owner.')->group(function(){
    // Login / Logout
    Route::get('/login', [PlatformOwnerLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PlatformOwnerLoginController::class, 'login'])->name('login.attempt');
    Route::post('/logout', [PlatformOwnerLoginController::class, 'logout'])->name('logout');

    // Forgot Password
    Route::get('/forgot-password', [PlatformOwnerPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PlatformOwnerPasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PlatformOwnerPasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PlatformOwnerPasswordResetController::class, 'reset'])->name('password.update');

    // Protected Dashboard
    Route::middleware('auth:platform_owner')->group(function () {
        Route::get('/dashboard', function () {
            return 'Welcome Platform Owner!';
        })->name('dashboard');
    });
});


// --- COMPANY ADMIN & STAFF ROUTES ---

// Public routes accessible without a prefix
Route::post('/login', [CompanyAdminLoginController::class, 'login'])->name('login.attempt');

Route::get('/register/company', [CompanyAdminRegisterController::class, 'showCompanyForm'])->name('register.company');
Route::post('/register/company', [CompanyAdminRegisterController::class, 'storeCompany'])->name('register.company.attempt');

// Verification code form
Route::get('/verify-code', [CompanyAdminPasswordResetController::class, 'showVerificationForm'])->name('password.verify.form');
Route::post('/verify-code', [CompanyAdminPasswordResetController::class, 'verifyCode'])->name('password.verify.code');

// --- PROTECTED ROUTES (Require Login) ---

// This group is for pages BOTH Admins and Staff can see
Route::middleware('auth:company_admin,company_staff')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// This group is ONLY for Company Admins
Route::middleware('auth:company_admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [CompanyUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [CompanyUserController::class, 'create'])->name('users.create');
    Route::post('/users', [CompanyUserController::class, 'store'])->name('users.store');
});


require __DIR__.'/auth.php';


Route::get('/invitation/accept/{token}', [InvitationController::class, 'accept'])->name('invitation.accept');
Route::post('/invitation/set-password', [InvitationController::class, 'storePassword'])->name('invitation.store_password');
