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
        Schema::create('delivery_draft_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_plan_id')->constrained('delivery_plans')->onDelete('cascade'); // Relasi ke rencana delivery
            $table->boolean('is_consigned')->default(false); // Apakah item titipan
            $table->string('item_name'); // Nama item yang akan dikirim
            $table->integer('quantity'); // Jumlah item
            $table->string('unit'); // Satuan item (pcs, kg, liter, dll.)
            $table->text('item_notes')->nullable(); // Keterangan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_draft_items');
    }
};
