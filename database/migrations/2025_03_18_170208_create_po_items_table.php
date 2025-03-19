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
        Schema::create('po_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('pos')->onDelete('cascade'); // Relasi ke PO
            $table->foreignId('spb_id')->constrained('spbs')->onDelete('cascade'); // Relasi ke SPB
            $table->foreignId('workshop_spb_id')->nullable()->constrained('workshop_spbs')->onDelete('set null'); // Relasi ke Workshop SPB jika item dari workshop
            $table->foreignId('site_spb_id')->nullable()->constrained('site_spbs')->onDelete('set null'); // Relasi ke Site SPB jika item dari site
            $table->string('item_name'); // Nama item yang dipesan
            $table->string('unit'); // Satuan barang
            $table->integer('quantity'); // Jumlah barang yang dipesan
            $table->decimal('unit_price', 15, 2)->nullable(); // Harga satuan barang
            $table->decimal('total_price', 15, 2)->nullable(); // Total harga barang (quantity * unit_price)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_items');
    }
};
