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
        Schema::create('division_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('division_id')->constrained()->onDelete('cascade');
            $table->string('report_number');
            $table->date('report_date');
            $table->enum('report_type', ['daily', 'weekly', 'monthly']);
            $table->text('progress_summary');
            $table->text('challenges')->nullable();
            $table->text('next_plan')->nullable();
            $table->json('attachments')->nullable();
            // Tambahkan kolom untuk relasi ke tasks
            $table->json('related_tasks')->nullable(); // Menyimpan array task IDs
            $table->json('task_progress')->nullable(); // Menyimpan progress per task
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('acknowledged_by')->nullable()->constrained('users');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('division_reports');
    }
};
