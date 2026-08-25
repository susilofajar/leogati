<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number', 30)->unique()->comment('Nomor PO: PO-YYYYMMDD-XXXX');
            $table->foreignId('supplier_id')
                  ->constrained('suppliers')
                  ->onDelete('restrict');
            $table->foreignId('warehouse_id')
                  ->constrained('warehouses')
                  ->onDelete('restrict')
                  ->comment('Gudang tujuan penerimaan barang');
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('restrict');
            $table->enum('status', [
                'draft',     // PO masih diedit
                'sent',      // Sudah dikirim ke supplier
                'partial',   // Sebagian barang sudah diterima
                'received',  // Semua barang sudah diterima
                'cancelled', // PO dibatalkan
            ])->default('draft');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->date('expected_at')->nullable()->comment('Estimasi tanggal tiba barang');
            $table->date('received_at')->nullable()->comment('Tanggal semua barang diterima');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('supplier_id');
            $table->index('warehouse_id');
            $table->index('created_by');
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')
                  ->constrained('purchase_orders')
                  ->onDelete('cascade');
            $table->foreignId('product_variant_id')
                  ->constrained('product_variants')
                  ->onDelete('restrict');
            $table->unsignedInteger('quantity_ordered')->comment('Jumlah yang dipesan ke supplier');
            $table->unsignedInteger('quantity_received')->default(0)->comment('Jumlah yang sudah diterima');
            $table->decimal('unit_cost', 15, 2)->comment('Harga beli per unit dari supplier');
            $table->decimal('subtotal', 15, 2)->storedAs('quantity_ordered * unit_cost');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('purchase_order_id');
            $table->index('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
    }
};
