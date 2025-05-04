<?php

use App\Http\Controllers\Purchasing\PoController;
use App\Http\Controllers\Purchasing\SpbController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:Purchasing'])->prefix('purchasing')->name('purchasing.')->group(function () {
    Route::get('/spbs', [SpbController::class, 'index'])->name('spbs.index');
    Route::get('/spbs/{spbs}', [SpbController::class, 'show'])->name('spbs.show');
    Route::get('/pos', [PoController::class, 'index'])->name('pos.index');
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
    Route::get('suppliers', function () {
        return view('purchasing.suppliers.index');
    })->name('suppliers.index');
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
