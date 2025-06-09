<?php

use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\OutgoingController;
use App\Http\Controllers\Inventory\ReceivedGoodsController;
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

    Route::resource('received-goods', ReceivedGoodsController::class)->only(['index', 'create', 'store']);

    // With these specific routes
    Route::get('received-goods', [ReceivedGoodsController::class, 'index'])->name('received-goods.index');
    Route::get('received-goods/create/{po}', [ReceivedGoodsController::class, 'create'])->name('received-goods.create');
    Route::post('received-goods/store-item', [ReceivedGoodsController::class, 'storeItem'])->name('received-goods.store-item');
    Route::post('received-goods/{po}', [ReceivedGoodsController::class, 'store'])->name('received-goods.store');

    Route::get('/outgoing', [OutgoingController::class, 'index'])->name('outgoing.index');
    Route::get('/outgoing/{transaction}', [OutgoingController::class, 'show'])->name('outgoing.show');
    Route::get('/outgoing/export', [OutgoingController::class, 'export'])->name('outgoing.export');

    Route::get('/reports', function () {
        return view('inventory.reports');
    })->name('reports');

    Route::get('/settings', function () {
        return view('inventory.settings');
    })->name('settings');
});
