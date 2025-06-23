<?php

use App\Http\Controllers\Auth\PlatformOwnerLoginController;
use App\Http\Controllers\PlatformOwner\AnalyticsController;
use App\Http\Controllers\PlatformOwner\CompanyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// --- PLATFORM OWNER AUTHENTICATION ---

// Routes for the authenticated Platform Owner
Route::middleware('auth:platform_owner')->group(function() {

    // Dashboard view
    Route::get('/dashboard', [CompanyController::class, 'index'])->name('owner.dashboard');

    // Profile view
    Route::get('/profile', [ProfileController::class, 'edit'])->name('owner.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('owner.profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('owner.password.update');
    Route::patch('/profile/photo', [\App\Http\Controllers\ProfileController::class, 'updatePhoto'])->name('owner.profile.updatePhoto');

    // Company view
    Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('company.show');
    Route::post('/companies/{company}/reactivate', [CompanyController::class, 'reactivate'])->name('company.reactivate');

    // Analytics view
    Route::get('/analytics-report', [AnalyticsController::class, 'index'])->name('analytics.report');
});

require __DIR__.'/auth.php';
