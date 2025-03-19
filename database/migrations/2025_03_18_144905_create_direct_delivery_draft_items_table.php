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
        Schema::create('direct_delivery_draft_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('direct_delivery_id')->constrained('direct_deliveries')->onDelete('cascade'); // Relasi ke direct delivery
            $table->string('item_name'); // Nama item yang dikirim
            $table->string('unit'); // Satuan item (pcs, kg, liter, dll.)
            $table->integer('quantity'); // Jumlah item
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direct_delivery_draft_items');
    }
};
