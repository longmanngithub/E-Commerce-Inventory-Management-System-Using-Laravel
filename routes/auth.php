<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PlatformOwnerLoginController;
use App\Http\Controllers\Auth\PlatformOwnerPasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// This middleware group is for GUESTS of Platform Owner
Route::middleware('guest:platform_owner')->group(function () {
    // --- LOGIN ---
    Route::get('login', [PlatformOwnerLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [PlatformOwnerLoginController::class, 'login']);

    // --- FORGOT PASSWORD (Multi-Step) ---
    Route::get('forgot-password', [PlatformOwnerPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [PlatformOwnerPasswordResetController::class, 'sendCode'])->name('password.email');
    Route::get('verify-code', [PlatformOwnerPasswordResetController::class, 'showVerificationForm'])->name('password.verify.form');
    Route::post('verify-code', [PlatformOwnerPasswordResetController::class, 'verifyCode'])->name('password.verify.code');
    Route::get('reset-password', [PlatformOwnerPasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [PlatformOwnerPasswordResetController::class, 'updatePassword'])->name('password.update');
});

// --- LOGOUT ---
// This route is for LOGGED-IN users
Route::middleware('auth:platform_owner')->post('logout', [PlatformOwnerLoginController::class, 'logout'])->name('logout');
