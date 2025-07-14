<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('delivery_plans', function (Blueprint $table) {
            $table->string('proof_of_delivery_path')->nullable()->after('delivery_notes');
            // Change the column type to include 'delivering' status
            $table->enum('status', ['draft', 'packing', 'ready', 'delivering', 'completed', 'cancelled'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_plans', function (Blueprint $table) {
            $table->dropColumn('proof_of_delivery_path');
            // Revert the column type
            $table->enum('status', ['draft', 'packing', 'ready', 'completed', 'cancelled'])->default('draft')->change();
        });
    }
};
