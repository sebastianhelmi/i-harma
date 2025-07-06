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
        Schema::create('report_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_report_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('task_id')
                ->constrained()
                ->onDelete('cascade');
            $table->text('progress_notes');
            $table->timestamps();

            // Optional: Add unique constraint to prevent duplicate entries
            $table->unique(['division_report_id', 'task_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_tasks');
    }
};
