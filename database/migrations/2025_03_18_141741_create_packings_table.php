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
        Schema::create('packings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_plan_id')->constrained('delivery_plans')->onDelete('cascade'); // Relasi ke rencana delivery
            $table->enum('packing_type', ['box', 'bundle', 'loose']); // Jenis packing
            $table->string('packing_category'); // Kategori packing
            $table->string('packing_dimensions'); // Dimensi packing (misal: 100x50x30 cm)
            $table->string('packing_number'); // Nomor packing unik
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packings');
    }
};
