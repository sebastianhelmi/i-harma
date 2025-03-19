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
        Schema::create('site_spbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spb_id')->constrained('spbs')->onDelete('cascade');
            $table->string('item_name');
            $table->string('unit');
            $table->integer('quantity');
            $table->text('information');
            $table->json('document_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_spbs');
    }
};
