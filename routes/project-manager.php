<?php

use Illuminate\Support\Facades\Route;

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
