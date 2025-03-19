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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable(); // Deskripsi proyek
            $table->date('start_date'); // Tanggal mulai proyek
            $table->date('end_date')->nullable(); // Tanggal selesai proyek (opsional)
            $table->enum('status', ['pending', 'ongoing', 'completed'])->default('pending'); // Status proyek
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade'); // Manager proyek
            $table->json('files')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
