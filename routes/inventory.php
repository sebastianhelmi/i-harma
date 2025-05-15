<?php

use App\Http\Controllers\Inventory\InventoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:Inventory'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/dashboard', function () {
        return view('inventory.dashboard');
    })->name('dashboard');

    Route::get('/items', [InventoryController::class, 'index'])->name('items.index');
    Route::get('/items/create', [InventoryController::class, 'create'])->name('items.create');
    Route::post('/items', [InventoryController::class, 'store'])->name('items.store');
    Route::get('/items/{item}/edit', [InventoryController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [InventoryController::class, 'update'])->name('items.update');

    Route::get('/incoming', function () {
        return view('inventory.incoming');
    })->name('incoming');

    Route::get('/outgoing', function () {
        return view('inventory.outgoing');
    })->name('outgoing');

    Route::get('/reports', function () {
        return view('inventory.reports');
    })->name('reports');

    Route::get('/settings', function () {
        return view('inventory.settings');
    })->name('settings');
});
