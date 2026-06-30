<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoAccessController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Secure Streaming Route (Controller handles specific authorization logic)
    Route::get('/videos/{video}/stream', [StreamController::class, 'stream'])->name('video.stream');

    // Redirect Root to Dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Admin Group
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

        // Customer CRUD
        Route::get('/admin/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
        Route::post('/admin/customers', [CustomerController::class, 'store'])->name('admin.customers.store');
        Route::put('/admin/customers/{customer}', [CustomerController::class, 'update'])->name('admin.customers.update');
        Route::delete('/admin/customers/{customer}', [CustomerController::class, 'destroy'])->name('admin.customers.destroy');

        // Video CRUD
        Route::get('/admin/videos', [VideoController::class, 'index'])->name('admin.videos.index');
        Route::post('/admin/videos', [VideoController::class, 'store'])->name('admin.videos.store');
        Route::put('/admin/videos/{video}', [VideoController::class, 'update'])->name('admin.videos.update');
        Route::delete('/admin/videos/{video}', [VideoController::class, 'destroy'])->name('admin.videos.destroy');

        // Access Requests Management
        Route::get('/admin/access-requests', [VideoAccessController::class, 'index'])->name('admin.requests.index');
        Route::post('/admin/access-requests/{access}/approve', [VideoAccessController::class, 'approve'])->name('admin.requests.approve');
        Route::post('/admin/access-requests/{access}/reject', [VideoAccessController::class, 'reject'])->name('admin.requests.reject');
        Route::post('/admin/access-requests/{access}/revoke', [VideoAccessController::class, 'revoke'])->name('admin.requests.revoke');
    });

    // Customer Group
    Route::middleware('role:customer')->group(function () {
        Route::get('/customer/dashboard', [DashboardController::class, 'customerDashboard'])->name('customer.dashboard');
        
        // Request access to a video
        Route::post('/customer/request-access/{video}', [VideoAccessController::class, 'requestAccess'])->name('customer.requests.request');
        
        // Watch the video (includes countdown client logic)
        Route::get('/customer/watch/{video}', [DashboardController::class, 'watch'])->name('customer.watch');
    });
});
