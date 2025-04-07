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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade'); // User yang membuat laporan
            $table->enum('type', ['project', 'spb', 'po', 'inventory', 'delivery']); // Jenis laporan
            $table->text('description')->nullable(); // Deskripsi laporan
            $table->json('report_data'); // Data laporan dalam format JSON
            $table->timestamp('generated_at')->useCurrent(); // Waktu laporan dibuat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
