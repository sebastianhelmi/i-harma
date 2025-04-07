<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade'); // Relasi ke tugas
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade'); // User yang melaporkan (Kepala Divisi)
            $table->enum('status', ['completed', 'pending', 'overdue'])->default('pending'); // Status tugas saat dilaporkan
            $table->text('report_details'); // Detail laporan tugas
            $table->dateTime('reported_at')->default(now()); // Waktu laporan dibuat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_reports');
    }
};
