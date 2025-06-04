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
        Schema::create('workshop_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
        $table->foreignId('spb_id')->constrained('spbs')->onDelete('cascade');
        $table->foreignId('workshop_spb_id')->nullable()->constrained('workshop_spbs')->onDelete('set null'); // Make nullable
        $table->foreignId('inventory_id')->nullable()->constrained('inventories')->onDelete('set null');
        $table->integer('quantity_produced');
        $table->enum('status', ['pending', 'completed'])->default('pending');
        $table->text('notes')->nullable();
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshop_outputs');
    }
};
