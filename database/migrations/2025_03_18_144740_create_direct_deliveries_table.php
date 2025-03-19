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
        Schema::create('direct_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_note_id')->constrained('delivery_notes')->onDelete('cascade'); // Relasi ke Surat Jalan
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade'); // Relasi ke proyek
            $table->string('expedition'); // Nama ekspedisi atau pihak pengirim
            $table->date('delivery_date'); // Tanggal pengiriman
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direct_deliveries');
    }
};
