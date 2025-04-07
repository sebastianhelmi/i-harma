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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade'); // Barang yang terlibat
            $table->foreignId('po_id')->nullable()->constrained('pos')->onDelete('set null'); // Relasi ke PO jika barang masuk dari pembelian
            $table->foreignId('delivery_id')->nullable()->constrained('delivery_plans')->onDelete('set null'); // Relasi ke pengiriman jika barang keluar
            $table->integer('quantity'); // Jumlah barang dalam transaksi
            $table->enum('transaction_type', ['IN', 'OUT']); // Tipe transaksi (Masuk/Keluar)
            $table->date('transaction_date'); // Tanggal transaksi
            $table->foreignId('handled_by')->constrained('users')->onDelete('cascade'); // User yang menangani transaksi
            $table->text('remarks')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
