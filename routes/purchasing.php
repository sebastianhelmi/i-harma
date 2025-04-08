<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:Purchasing'])->group(function () {
    Route::get('/purchasing/dashboard', function () {
        return view('purchasing.dashboard');
    })->name('purchasing.dashboard');
    Route::get('/purchasing/orders', function () {
        return view('purchasing.orders.index');
    })->name('purchasing.orders.index');
    Route::get('/purchasing/suppliers', function () {
        return view('purchasing.suppliers.index');
    })->name('purchasing.suppliers.index');
    Route::get('/purchasing/reports', function () {
        return view('purchasing.reports');
    })->name('purchasing.reports');
    Route::get('/purchasing/settings', function () {
        return view('purchasing.settings');
    })->name('purchasing.settings');
    Route::get('/purchasing/spb', function () {
        return view('purchasing.spb');
    })->name('purchasing.spb');
});
