<?php

use App\Http\Controllers\Purchasing\PoController;
use App\Http\Controllers\Purchasing\SpbController;
use App\Http\Controllers\Purchasing\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:Purchasing'])->prefix('purchasing')->name('purchasing.')->group(function () {
    Route::get('/spbs', [SpbController::class, 'index'])->name('spbs.index');

    Route::get('/spbs/{spb}', [SpbController::class, 'show'])->name('spbs.show');
    Route::patch('/spbs/{spb}/mark-not-required', [PoController::class, 'markAsNotRequired'])
        ->name('spbs.mark-not-required');
    Route::get('/pos', [PoController::class, 'index'])->name('pos.index');
    Route::get('/pos/{po}', [PoController::class, 'show'])->name('pos.show');
    Route::get('/pos/{po}/edit', [PoController::class, 'edit'])->name('pos.edit');
    Route::put('/pos/{po}', [PoController::class, 'update'])->name('pos.update');
    Route::get('/pos/{po}/print', [PoController::class, 'print'])->name('pos.print');

    Route::patch('/pos/{po}/complete', [PoController::class, 'complete'])->name('pos.complete');
    Route::patch('/pos/{po}/cancel', [PoController::class, 'cancel'])->name('pos.cancel');
    Route::get('/pos/create/{spb}', [PoController::class, 'create'])->name('pos.create');
    Route::post('/pos', [PoController::class, 'store'])->name('pos.store');
    Route::get('dashboard', function () {
        return view('purchasing.dashboard');
    })->name('dashboard');
    Route::get('orders', function () {
        return view('purchasing.orders.index');
    })->name('orders.index');
    Route::resource('suppliers', SupplierController::class);
    Route::get('reports', function () {
        return view('purchasing.reports');
    })->name('reports');
    Route::get('settings', function () {
        return view('purchasing.settings');
    })->name('settings');
    Route::get('spb', function () {
        return view('purchasing.spb');
    })->name('spb');
});
