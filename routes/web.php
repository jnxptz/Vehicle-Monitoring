<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\FuelSlipController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\DashboardController; // Added for boardmember dashboard logic
use App\Http\Controllers\OfficeController;


// --------------------
// Landing page
// --------------------
Route::get('/', fn() => view('welcome'));

// --------------------
// Auth routes
// --------------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordReset'])->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// --------------------
// Dashboard redirect based on role
// --------------------
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login.form');
    }

    return Auth::user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('boardmember.dashboard');
});

// --------------------
// Role-based dashboards
// --------------------

// Admin dashboard
Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');

// Boardmember dashboard — use DashboardController to calculate remaining budget, monthly usage
Route::get('/boardmember/dashboard', [DashboardController::class, 'boardmember'])
    ->middleware(['auth', 'role:boardmember'])
    ->name('boardmember.dashboard');

// Boardmember dashboard PDF export (respects ?month=)
Route::get('/boardmember/dashboard/pdf', [DashboardController::class, 'exportPdf'])
    ->middleware(['auth', 'role:boardmember'])
    ->name('boardmember.dashboard.pdf');

// Boardmember yearly PDF export (January to December)
Route::get('/boardmember/dashboard/yearly-pdf', [DashboardController::class, 'exportYearlyPdf'])
    ->middleware(['auth', 'role:boardmember'])
    ->name('boardmember.dashboard.yearly.pdf');

// Admin yearly PDF export (fleet-wide)
Route::get('/admin/dashboard/yearly-pdf', [DashboardController::class, 'exportAdminYearlyPdf'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard.yearly.pdf');

// --------------------
// Admin-only routes
// --------------------
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Office management custom routes (must come BEFORE resource route)
    Route::get('offices/manage-boardmembers', [OfficeController::class, 'manageBoardmembers'])->name('offices.manage-boardmembers');
    Route::put('offices/assign-boardmember/{user}', [OfficeController::class, 'assignBoardmember'])->name('offices.assign-boardmember');
    
    // Office resource (CRUD)
    Route::resource('offices', OfficeController::class);
    
    Route::resource('vehicles', VehicleController::class);
    
    // Admin can create fuel slips
    Route::get('fuel-slips/create', [FuelSlipController::class, 'create'])->name('fuel-slips.create');
    Route::post('fuel-slips', [FuelSlipController::class, 'store'])->name('fuel-slips.store');
});

// --------------------
// Boardmember-only routes
// --------------------
Route::middleware(['auth', 'role:boardmember'])->group(function () {
    // Boardmembers can only view their fuel slips; creation is handled by admin
});

// --------------------
// Fuel slip routes
// - index + pdf: admin + boardmember (boardmember scoped in controller)
// - create/store: boardmember only (above)
// --------------------
Route::middleware(['auth'])->group(function () {
    Route::get('fuel-slips', [FuelSlipController::class, 'index'])->name('fuel-slips.index');
    Route::get('fuel-slips/{id}/pdf', [FuelSlipController::class, 'exportPDF'])->name('fuel-slips.exportPDF');
});

// --------------------
// Maintenance routes
// - index + pdf: admin + boardmember (boardmember is read-only + scoped)
// - create/store: admin only
// --------------------
Route::middleware(['auth'])->group(function () {
    Route::get('maintenances', [MaintenanceController::class, 'index'])->name('maintenances.index');
    Route::get('maintenances/{id}/pdf', [MaintenanceController::class, 'exportPDF'])->name('maintenances.exportPDF');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('maintenances/create', [MaintenanceController::class, 'create'])->name('maintenances.create');
    Route::post('maintenances', [MaintenanceController::class, 'store'])->name('maintenances.store');
});


// Note: vehicle creation/management is admin-only via the resource above