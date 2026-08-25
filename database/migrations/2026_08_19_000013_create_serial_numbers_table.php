<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah flag is_serialized ke product_variants
        Schema::table('product_variants', function (Blueprint $table) {
            $table->boolean('is_serialized')->default(false)
                  ->after('is_active')
                  ->comment('Apakah varian ini perlu dilacak per nomor seri');
        });

        Schema::create('serial_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique()->comment('Nomor seri unik unit produk');
            $table->foreignId('product_variant_id')
                  ->constrained('product_variants')
                  ->onDelete('cascade');
            $table->foreignId('warehouse_id')
                  ->nullable()
                  ->constrained('warehouses')
                  ->onDelete('set null');
            $table->foreignId('purchase_order_id')
                  ->nullable()
                  ->constrained('purchase_orders')
                  ->onDelete('set null');
            $table->foreignId('order_item_id')
                  ->nullable()
                  ->constrained('order_items')
                  ->onDelete('set null');
            $table->foreignId('customer_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->enum('status', [
                'available',  // Tersedia di gudang
                'reserved',   // Direservasi untuk pesanan
                'sold',       // Sudah terjual
                'returned',   // Dikembalikan pelanggan
                'damaged',    // Rusak / cacat
                'warranty',   // Dalam proses klaim garansi
            ])->default('available');
            $table->date('purchased_at')->nullable()->comment('Tanggal barang diterima dari supplier');
            $table->date('sold_at')->nullable()->comment('Tanggal terjual ke pelanggan');
            $table->date('warranty_expires_at')->nullable()->comment('Tanggal kadaluarsa garansi');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('product_variant_id');
            $table->index('customer_id');
            $table->index('purchase_order_id');
            $table->index('order_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('serial_numbers');
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('is_serialized');
        });
    }
};
