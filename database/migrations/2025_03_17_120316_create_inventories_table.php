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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('item_name'); // Nama barang
            $table->string('category')->nullable(); // Kategori barang
            $table->integer('quantity')->default(0); // Jumlah stok
            $table->integer('initial_stock')->default(0); // Stok awal
            $table->foreignId('item_category_id')->constrained('item_categories')->onDelete('restrict'); // Relasi ke kategori item
            $table->string('unit'); // Satuan barang (pcs, kg, liter, dll.)
            $table->decimal('unit_price', 15, 2)->nullable(); // Harga satuan
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade'); // User yang menambahkan stok
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
