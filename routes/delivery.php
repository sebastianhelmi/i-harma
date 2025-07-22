<?php

use App\Http\Controllers\Delivery\DashboardController;
use App\Http\Controllers\Delivery\DeliveryConfirmationController;
use App\Http\Controllers\Delivery\DeliveryNoteController;
use App\Http\Controllers\Delivery\DeliveryPlanController;
use App\Http\Controllers\Delivery\DeliveryPlanItemController;
use App\Http\Controllers\Delivery\ShipmentController;
use App\Http\Controllers\Delivery\HistoryController;
use App\Http\Controllers\Delivery\PackingController;
use App\Http\Controllers\Delivery\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:Delivery'])->prefix('delivery')->name('delivery.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Delivery Plans (Rencana Pengiriman)
    Route::prefix('plans')->name('plans.')->group(function () {
        Route::get('/', [DeliveryPlanController::class, 'index'])->name('index');
        Route::get('/create', [DeliveryPlanController::class, 'create'])->name('create');
        Route::post('/', [DeliveryPlanController::class, 'store'])->name('store');
        Route::get('/{plan}', [DeliveryPlanController::class, 'show'])->name('show');
        Route::get('/{plan}/edit', [DeliveryPlanController::class, 'edit'])->name('edit');
        Route::put('/{plan}', [DeliveryPlanController::class, 'update'])->name('update');
        Route::delete('/{plan}', [DeliveryPlanController::class, 'destroy'])->name('destroy');
        Route::patch('/{plan}/status', [DeliveryPlanController::class, 'updateStatus'])->name('update-status');

        // Delivery Confirmation Routes
        Route::get('/{plan}/confirm', [DeliveryConfirmationController::class, 'showConfirmationForm'])->name('confirm.form');
        Route::post('/{plan}/confirm', [DeliveryConfirmationController::class, 'confirmDelivery'])->name('confirm.store');

        // Add these new routes for item management
        Route::post('/{plan}/items', [DeliveryPlanItemController::class, 'store'])->name('items.store');
        Route::delete('/items/{item}', [DeliveryPlanItemController::class, 'destroy'])->name('items.destroy');

        Route::get('/{plan}/packings/create', [PackingController::class, 'create'])->name('packings.create');
        Route::post('/{plan}/packings', [PackingController::class, 'store'])->name('packings.store');
        Route::delete('/{plan}/packings/{packing}', [PackingController::class, 'destroy'])->name('packings.destroy');

        Route::get('/{plan}/items/create', [DeliveryPlanItemController::class, 'create'])->name('items.create');
    });

    Route::prefix('notes')->name('notes.')->group(function () {
        Route::get('/create/{plan}', [DeliveryNoteController::class, 'create'])->name('create');
        Route::post('/{plan}', [DeliveryNoteController::class, 'store'])->name('store');
        Route::get('/{note}', [DeliveryNoteController::class, 'show'])->name('show');
        Route::get('/{plan}/items/create', [DeliveryPlanItemController::class, 'create'])->name('items.create');
        Route::get('/{note}/print', [DeliveryNoteController::class, 'print'])->name('print');
    });

    // // Shipments (Pengiriman)
    // Route::prefix('shipments')->name('shipments.')->group(function () {
    //     Route::get('/', [ShipmentController::class, 'index'])->name('index');
    //     Route::get('/create/{plan}', [ShipmentController::class, 'create'])->name('create');
    //     Route::post('/', [ShipmentController::class, 'store'])->name('store');
    //     Route::get('/{shipment}', [ShipmentController::class, 'show'])->name('show');
    //     Route::put('/{shipment}', [ShipmentController::class, 'update'])->name('update');
    //     Route::patch('/{shipment}/start', [ShipmentController::class, 'start'])->name('start');
    //     Route::patch('/{shipment}/complete', [ShipmentController::class, 'complete'])->name('complete');
    //     Route::post('/{shipment}/proof', [ShipmentController::class, 'uploadProof'])->name('upload-proof');
    // });

    // Delivery History (Riwayat Pengiriman)
    Route::prefix('history')->name('history.')->group(function () {
        Route::get('/', [HistoryController::class, 'index'])->name('index');
        Route::get('/{shipment}', [HistoryController::class, 'show'])->name('show');
        Route::get('/export', [HistoryController::class, 'export'])->name('export');
    });

    // Reports (Laporan)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/generate', [ReportController::class, 'generate'])->name('generate');
        Route::get('/download/{report}', [ReportController::class, 'download'])->name('download');
    });

    // Profile
    Route::get('/profile', function () {
        return view('delivery.profile');
    })->name('profile');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
});
