<?php

use App\Http\Controllers\HeadOfDivision\ProjectController;
use App\Http\Controllers\HeadOfDivision\SpbController;
use App\Http\Controllers\HeadOfDivision\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:Kepala Divisi'])->prefix('head-of-division')->name('head-of-division.')->group(function () {
    Route::get('/dashboard', function () {
        return view('head-of-division.dashboard');
    })->name('dashboard');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

    Route::resource('tasks', TaskController::class);
    Route::get('/spbs/create/{task?}', [SpbController::class, 'create'])->name('spbs.create');
    Route::resource('spbs', SpbController::class)->except(['create']);

});

Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/projects/{project}/tasks', [TaskController::class, 'getProjectTasks']);
});
