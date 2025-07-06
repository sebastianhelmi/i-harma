<?php

use App\Http\Controllers\HeadOfDivision\DeliveryConfirmationController;
use App\Http\Controllers\HeadOfDivision\ProjectController;
use App\Http\Controllers\HeadOfDivision\ReportController;
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
    Route::patch('/tasks/{id}/complete', [TaskController::class, 'complete'])->name('tasks.complete');

    Route::resource('tasks', TaskController::class);
    Route::get('/spbs/create/{task?}', [SpbController::class, 'create'])->name('spbs.create');
    Route::resource('spbs', SpbController::class)->except(['create']);
    Route::get('/spbs/{spb}/items', [SpbController::class, 'getItems'])->name('spbs.items');
    Route::patch('/spbs/{spb}/take-items', [SpbController::class, 'takeItems'])->name('spbs.take-items');

    Route::middleware('division:3')->group(function () {
        Route::get('/delivery-confirmations', [DeliveryConfirmationController::class, 'index'])
            ->name('delivery-confirmations.index');
        Route::get('/delivery-confirmations/{plan}', [DeliveryConfirmationController::class, 'show'])
            ->name('delivery-confirmations.show');
        Route::post('/delivery-confirmations/{plan}/approve', [DeliveryConfirmationController::class, 'approve'])
            ->name('delivery-confirmations.approve');
        Route::post('/delivery-confirmations/{plan}/reject', [DeliveryConfirmationController::class, 'reject'])
            ->name('delivery-confirmations.reject');
    });

    Route::resource('reports', ReportController::class);
});

Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/projects/{project}/tasks', [TaskController::class, 'getProjectTasks']);
});
