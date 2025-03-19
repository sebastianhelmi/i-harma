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
        Schema::create('workshop_spbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spb_id')->constrained('spbs')->onDelete('cascade');
            $table->text('explanation_items');
            $table->string('unit');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshop_spbs');
    }
};
