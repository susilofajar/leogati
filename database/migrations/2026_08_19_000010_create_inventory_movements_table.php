<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')
                  ->constrained('product_variants')
                  ->onDelete('cascade');
            $table->foreignId('warehouse_id')
                  ->constrained('warehouses')
                  ->onDelete('cascade');
            $table->enum('type', [
                'purchase',    // Penerimaan barang dari PO
                'sale',        // Penjualan (pesanan)
                'return',      // Pengembalian barang dari pelanggan
                'adjustment',  // Penyesuaian manual oleh staf gudang
                'transfer',    // Transfer antar gudang
                'damage',      // Barang rusak / cacat
                'reservation', // Reservasi stok untuk pesanan
                'release',     // Pelepasan reservasi
            ])->comment('Jenis mutasi stok');
            $table->integer('quantity_change')->comment('Positif = masuk, Negatif = keluar');
            $table->unsignedInteger('quantity_before')->comment('Stok sebelum mutasi');
            $table->unsignedInteger('quantity_after')->comment('Stok sesudah mutasi');
            $table->nullableMorphs('reference'); // Polymorphic: Order, PurchaseOrder, etc.
            $table->text('notes')->nullable()->comment('Catatan mutasi (alasan penyesuaian, dll)');
            $table->foreignId('performed_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('User yang melakukan mutasi');
            $table->timestamps();

            $table->index('type');
            $table->index(['product_variant_id', 'warehouse_id']);
            $table->index('performed_by');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
