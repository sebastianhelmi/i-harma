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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama tugas
            $table->text('description')->nullable(); // Deskripsi tugas
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade'); // Relasi ke proyek
            $table->foreignId('division_id')
                ->nullable()
                ->constrained('divisions')
                ->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // Ditugaskan ke siapa
            $table->date('due_date')->nullable(); // Deadline tugas
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending'); // Status tugas
            $table->foreignId('parent_task_id')->nullable()->constrained('tasks')->onDelete('cascade'); // Relasi ke tugas utama (subtask)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
