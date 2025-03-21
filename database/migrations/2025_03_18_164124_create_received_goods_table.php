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
        Schema::create('received_goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spb_id')->nullable()->constrained('spbs')->onDelete('cascade'); // Relasi ke SPB (opsional)
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade'); // Barang yang diterima
            $table->integer('quantity_received'); // Jumlah barang yang diterima
            $table->date('received_date'); // Tanggal penerimaan
            $table->foreignId('received_by')->constrained('users')->onDelete('cascade'); // User yang menerima barang
            $table->enum('status', ['pending', 'partially_received', 'completed'])->default('pending'); // Status penerimaan
            $table->text('remarks')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('received_goods');
    }
};
