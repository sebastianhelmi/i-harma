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
        Schema::table('workshop_outputs', function (Blueprint $table) {
            $table->boolean('need_delivery')->default(true)->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshop_outputs', function (Blueprint $table) {
            $table->dropColumn('need_delivery');
        });
    }
};
