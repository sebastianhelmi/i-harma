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
        Schema::create('delivery_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_note_id')->constrained('delivery_notes')->onDelete('cascade'); // Relasi ke surat jalan
            $table->string('vehicle_license_plate'); // Nomor polisi kendaraan
            $table->string('stnk_photo'); // Foto STNK
            $table->string('license_plate_photo'); // Foto nomor polisi kendaraan
            $table->string('vehicle_photo'); // Foto kendaraan
            $table->string('driver_license_photo'); // Foto SIM driver
            $table->string('driver_id_photo'); // Foto KTP driver
            $table->string('loading_process_photo'); // Foto proses muat barang

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_documents');
    }
};
