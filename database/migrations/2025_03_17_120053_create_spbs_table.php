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
        Schema::create('spbs', function (Blueprint $table) {
            $table->id();
            $table->string('spb_number')->unique(); // Nomor SPB yang unik
            $table->date('spb_date'); // Tanggal SPB
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade'); // Relasi ke proyek
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade'); // User yang mengajukan
            $table->foreignId('task_id')->nullable()->constrained('tasks')->onDelete('set null'); // Relasi ke task
            $table->foreignId('item_category_id')->constrained('item_categories')->onDelete('restrict'); // Kategori Item
            $table->enum('category_entry', ['workshop', 'site'])->default('site'); // Kategori SPB
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending'); // Status SPB
            $table->enum('status_po', ['not_required', 'pending', 'ordered', 'completed'])->default('not_required');
            $table->text('remarks')->nullable(); // Catatan tambahan
            $table->timestamp('approved_at')->nullable(); // Waktu persetujuan
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // User yang menyetujui
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spbs');
    }
};
