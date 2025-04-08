<?php

use Illuminate\Support\Facades\Route;

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
