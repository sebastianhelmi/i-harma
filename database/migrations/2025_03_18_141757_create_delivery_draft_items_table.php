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
            $table->foreignId('delivery_plan_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->integer('quantity');
            $table->string('unit');
            $table->boolean('is_consigned')->default(false);
            $table->text('item_notes')->nullable();
            $table->enum('source_type', ['inventory', 'workshop_output', 'site_spb', 'manual']);
            $table->unsignedBigInteger('source_id')->nullable();
            $table->foreignId('inventory_id')->nullable()->constrained()->nullOnDelete();
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
