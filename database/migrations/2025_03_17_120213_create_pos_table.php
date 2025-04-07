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
        Schema::create('pos', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique(); // Nomor PO yang unik
            $table->foreignId('spb_id')->constrained('spbs')->onDelete('cascade'); // Relasi ke SPB
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // User yang membuat PO
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade'); // Relasi ke Supplier
            $table->string('company_name'); // Nama perusahaan
            $table->date('order_date'); // Tanggal pembuatan PO
            $table->decimal('total_amount', 15, 2); // Total biaya pembelian
            $table->date('estimated_usage_date')->nullable(); // Estimasi tanggal penggunaan
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending'); // Status PO
            $table->text('remarks')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos');
    }
};
