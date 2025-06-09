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
            $table->foreignId('delivery_plan_id')->constrained('delivery_plans')->onDelete('cascade');
            $table->enum('packing_type', ['box', 'bundle', 'loose', 'pallet', 'crate', 'barrel', 'bag']); // Updated enum values
            $table->string('packing_category');
            $table->string('packing_dimensions');
            $table->string('packing_number');
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
