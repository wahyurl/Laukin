<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi +database untuk tabel-tabel LAUKIN.
     */
    public function up(): void
    {
        // 1. Tabel Kategori Menu
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // e.g., 'jajanan', 'makanan', 'minuman'
            $table->string('name');           // e.g., 'Jajanan & Snack'
            $table->string('icon')->nullable(); // e.g., '🍿'
            $table->timestamps();
        });

        // 2. Tabel Menu Kantin
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->integer('price');
            $table->text('image');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // 3. Tabel Pesanan Utama
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Format: "NO. 001"
            $table->string('customer_name');          // Nama & NIM Mahasiswa
            $table->integer('total_price');
            $table->enum('status', ['Diproses', 'Ready', 'Selesai'])->default('Diproses');
            $table->timestamps();
        });

        // 4. Tabel Detail Item Pesanan
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('menu_item_id')->nullable()->constrained('menu_items')->onDelete('set null');
            $table->string('menu_name'); // Simpan nama menu untuk arsip jika menu dihapus
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('categories');
    }
};