<?php

use App\Http\Controllers\ProjectManager\DashboardController;
use App\Http\Controllers\ProjectManager\ProcurementHistoryController;
use App\Http\Controllers\ProjectManager\ReportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectManager\SpbApprovalController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:Project Manager'])->prefix('pm')->name('pm.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('projects', ProjectController::class);
    Route::get('projects/{project}/export-excel', [ProjectController::class, 'exportExcel'])->name('projects.export-excel');
    Route::get('projects/{project}/summary-pdf', [ProjectController::class, 'exportSummaryPdf'])->name('projects.summary-pdf');
    Route::get('/spb', function () {
        return view('pm.spb');
    })->name('spb');

    Route::resource('tasks', TaskController::class);
    Route::get('projects/{project}/tasks/create', [TaskController::class, 'create'])->name('projects.tasks.create');

    Route::get('/spb-approvals', [SpbApprovalController::class, 'index'])->name('spb-approvals.index');
    Route::get('/spb-approvals/{spb}', [SpbApprovalController::class, 'show'])->name('spb-approvals.show');
    Route::post('/spb-approvals/{spb}/approve', [SpbApprovalController::class, 'approve'])->name('spb-approvals.approve');
    Route::post('/spb-approvals/{spb}/reject', [SpbApprovalController::class, 'reject'])->name('spb-approvals.reject');

     Route::get('/riwayat-pengadaan', [ProcurementHistoryController::class, 'index'])
        ->name('riwayat.index');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
});
