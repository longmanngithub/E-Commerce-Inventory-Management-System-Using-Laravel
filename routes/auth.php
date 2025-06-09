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
use Illuminate\Support\Facades\Route;

Route::middleware('guest:company_admin,company_staff,platform_owner')->group(function () {
    // Registration
    Route::get('register', [CompanyAdminRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [CompanyAdminRegisterController::class, 'register'])->name('register.attempt');

    // Login
    Route::get('login', [CompanyAdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CompanyAdminLoginController::class, 'login'])->name('login.attempt');

    // Forgot Password
    Route::get('forgot-password', [CompanyAdminPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [CompanyAdminPasswordResetController::class, 'sendVerificationCode'])->name('password.email');
    Route::get('/reset-password', [CompanyAdminPasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [CompanyAdminPasswordResetController::class, 'reset'])->name('password.update');
});

// --- LOGOUT ROUTES ---
// Logout for Company Admin or Staff
Route::middleware('auth:company_admin,company_staff')->post('logout', [CompanyAdminLoginController::class, 'logout'])->name('admin.logout');
