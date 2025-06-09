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
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_plan_id')->constrained('delivery_plans')->onDelete('cascade'); // Relasi ke rencana delivery
            $table->string('delivery_note_number')->unique(); // Nomor surat jalan unik
            $table->date('departure_date'); // Tanggal keberangkatan
            $table->date('estimated_arrival_date'); // Estimasi kedatangan
            $table->string('expedition')->nullable(); // Nama ekspedisi atau pengiriman
            $table->string('vehicle_license_plate'); // Nomor Polisi kendaraan
            $table->string('vehicle_type'); // Jenis kendaraan (misal: truk, mobil box)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Add this line
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_notes');
    }
};
