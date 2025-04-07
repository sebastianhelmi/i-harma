<?php

use App\Http\Controllers\Admin\InventoryManagementController;
use App\Http\Controllers\Admin\ItemCategoryManagement;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes with Role Middleware
Route::middleware(['auth', 'role:Project Manager'])->group(function () {
    Route::get('/pm/dashboard', function () {
        return view('pm.dashboard');
    })->name('pm.dashboard');
    Route::get('/pm/project', function () {
        return view('pm.project');
    })->name('pm.project');
    Route::get('/pm/tasks', function () {
        return view('pm.tasks');
    })->name('pm.tasks');
    Route::get('/pm/spb', function () {
        return view('pm.spb');
    })->name('pm.spb');
});

Route::prefix('admin')->middleware(['auth', 'role:Admin'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // User management routes
    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('admin.users.show');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::post('/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');

    // Additional user management routes
    Route::post('/users/bulk-destroy', [UserManagementController::class, 'bulkDestroy'])
        ->name('admin.users.bulk-destroy');
    Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])
        ->name('admin.users.toggle-status');
    // Item Category Management routes
    Route::get('/item-categories', [ItemCategoryManagement::class, 'index'])
        ->name('admin.item-categories.index');
    Route::post('/item-categories', [ItemCategoryManagement::class, 'store'])
        ->name('admin.item-categories.store');
    Route::get('/item-categories/{category}', [ItemCategoryManagement::class, 'show'])
        ->name('admin.item-categories.show');
    Route::put('/item-categories/{category}', [ItemCategoryManagement::class, 'update'])
        ->name('admin.item-categories.update');
    Route::delete('/item-categories/{category}', [ItemCategoryManagement::class, 'destroy'])
        ->name('admin.item-categories.destroy');
    Route::post('/item-categories/bulk-destroy', [ItemCategoryManagement::class, 'bulkDestroy'])
        ->name('admin.item-categories.bulk-destroy');

    // Inventory Management routes
    Route::get('/inventory', [InventoryManagementController::class, 'index'])
        ->name('admin.inventory.index');
    Route::post('/inventory', [InventoryManagementController::class, 'store'])
        ->name('admin.inventory.store');
    Route::get('/inventory/{inventory}', [InventoryManagementController::class, 'show'])
        ->name('admin.inventory.show');
    Route::put('/inventory/{inventory}', [InventoryManagementController::class, 'update'])
        ->name('admin.inventory.update');
    Route::delete('/inventory/{inventory}', [InventoryManagementController::class, 'destroy'])
        ->name('admin.inventory.destroy');
    Route::patch('/inventory/{inventory}/stock', [InventoryManagementController::class, 'updateStock'])
        ->name('admin.inventory.update-stock');
});

Route::middleware(['auth', 'role:Purchasing'])->group(function () {
    Route::get('/purchasing/dashboard', function () {
        return view('purchasing.dashboard');
    })->name('purchasing.dashboard');
    Route::get('/purchasing/spb', function () {
        return view('purchasing.spb');
    })->name('purchasing.spb');
});

Route::middleware(['auth', 'role:Kepala Divisi'])->group(function () {
    Route::get('/head-of-division/dashboard', function () {
        return view('head-of-division.dashboard');
    })->name('head-of-division.dashboard');
    Route::get('/head-of-division/projects', function () {
        return view('head-of-division.projects');
    })->name('head-of-division.projects');
    Route::get('/head-of-division/tasks', function () {
        return view('head-of-division.tasks');
    })->name('head-of-division.tasks');
    Route::get('/head-of-division/spb', function () {
        return view('head-of-division.spb');
    })->name('head-of-division.spb');
});
