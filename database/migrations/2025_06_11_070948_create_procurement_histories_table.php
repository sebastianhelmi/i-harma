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
        Schema::create('procurement_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spb_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('po_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('document_type', ['spb', 'po']);
            $table->string('document_number');
            $table->string('status');
            $table->foreignId('actor')->constrained('users')->cascadeOnDelete();
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_histories');
    }
};
