<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:Inventory'])->group(function () {
    Route::get('/inventory/dashboard', function () {
        return view('inventory.dashboard');
    })->name('inventory.dashboard');

    Route::get('/inventory/items', function () {
        return view('inventory.items.index');
    })->name('inventory.items.index');

    Route::get('/inventory/incoming', function () {
        return view('inventory.incoming');
    })->name('inventory.incoming');

    Route::get('/inventory/outgoing', function () {
        return view('inventory.outgoing');
    })->name('inventory.outgoing');

    Route::get('/inventory/reports', function () {
        return view('inventory.reports');
    })->name('inventory.reports');

    Route::get('/inventory/settings', function () {
        return view('inventory.settings');
    })->name('inventory.settings');
});
