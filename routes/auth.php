<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\CompanyAdminLoginController;
use App\Http\Controllers\Auth\CompanyAdminRegisterController;
use App\Http\Controllers\Auth\CompanyAdminPasswordResetController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

// This middleware group is for GUESTS of any of our company guards
Route::middleware('guest:company_admin,company_staff')->group(function () {
    // --- LOGIN ---
    Route::get('login', [CompanyAdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CompanyAdminLoginController::class, 'login'])->name('login.attempt');

    // --- REGISTRATION (Multi-Step) ---
    Route::get('register', [CompanyAdminRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [CompanyAdminRegisterController::class, 'showCompanyForm'])->name('register.attempt');
    Route::post('register/company', [CompanyAdminRegisterController::class, 'storeRegistration'])->name('register.company.store');

    // --- FORGOT PASSWORD (Multi-Step) ---
    Route::get('forgot-password', [CompanyAdminPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [CompanyAdminPasswordResetController::class, 'sendVerificationCode'])->name('password.email');
    Route::get('verify-code', [CompanyAdminPasswordResetController::class, 'showVerificationForm'])->name('password.verify.form');
    Route::post('verify-code', [CompanyAdminPasswordResetController::class, 'verifyCode'])->name('password.verify.code');
    Route::get('reset-password', [CompanyAdminPasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [CompanyAdminPasswordResetController::class, 'reset'])->name('password.update');
});

// --- LOGOUT ---
// This route is for LOGGED-IN users
Route::middleware('auth:company_admin,company_staff')->post('logout', [CompanyAdminLoginController::class, 'logout'])->name('logout');
