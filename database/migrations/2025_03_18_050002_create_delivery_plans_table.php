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
            $table->string('plan_number')->unique(); // Nomor rencana pengiriman
            $table->string('destination'); // Lokasi tujuan
            $table->date('planned_date'); // Tanggal rencana pengiriman
            $table->integer('vehicle_count'); // Jumlah kendaraan yang dibutuhkan
            $table->enum('vehicle_type', ['truck', 'pickup', 'box', 'container']); // Jenis kendaraan
            $table->text('delivery_notes')->nullable(); // Catatan pengiriman
            $table->enum('status', ['draft', 'packing', 'ready', 'completed', 'cancelled'])
                ->default('draft'); // Status rencana
            $table->foreignId('created_by')->constrained('users'); // Pembuat rencana
            $table->foreignId('updated_by')->nullable()->constrained('users'); // Pengubah terakhir
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
