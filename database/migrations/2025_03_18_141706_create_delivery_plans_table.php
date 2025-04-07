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
        Schema::create('delivery_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spb_id')->constrained('spbs')->onDelete('cascade'); // Relasi ke SPB
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('restrict'); // Relasi ke Inventory
            $table->date('planned_delivery_date'); // Tanggal rencana pengiriman
            $table->string('destination_site'); // Site tujuan pengiriman
            $table->integer('vehicle_count'); // Jumlah kendaraan
            $table->text('delivery_notes')->nullable(); // Keterangan pengiriman
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_plans');
    }
};
