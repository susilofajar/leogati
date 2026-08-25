<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Stok per varian per gudang
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')
                  ->constrained('product_variants')
                  ->onDelete('cascade');
            $table->foreignId('warehouse_id')
                  ->constrained('warehouses')
                  ->onDelete('cascade');
            $table->unsignedInteger('quantity')->default(0)->comment('Stok aktual di gudang ini');
            $table->unsignedInteger('reserved_quantity')->default(0)->comment('Stok yang direservasi untuk pesanan pending');
            $table->timestamps();

            // Setiap varian hanya memiliki 1 record per gudang
            $table->unique(['product_variant_id', 'warehouse_id']);
            $table->index('warehouse_id');
            $table->index('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
