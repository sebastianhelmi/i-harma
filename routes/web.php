<?php

use App\Http\Controllers\Admin\InventoryManagementController;
use App\Http\Controllers\Admin\ItemCategoryManagement;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes with Role Middleware
require __DIR__ . '/project-manager.php';

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

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/read/{id}', function ($id) {
        $notification = Auth::user()->notifications->where('id', $id)->firstOrFail();
        $notification->markAsRead();
        $url = request('redirect', '/');
        return redirect($url);
    })->name('notifications.read');
});


require __DIR__ . '/purchasing.php';
require __DIR__ . '/inventory.php';
require __DIR__ . '/head-of-division.php';
require __DIR__ . '/delivery.php';
