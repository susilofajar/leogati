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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('subtotal_amount', 15, 2);
            $table->decimal('shipping_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->string('status')->default('awaiting_payment'); // pending, awaiting_payment, paid, processing, packed, shipped, delivered, completed, cancelled, refunded
            $table->string('payment_method')->default('bank_transfer'); // bca_va, mandiri_va, bri_va, qris, bank_transfer
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, failed, refunded
            $table->string('shipping_courier')->nullable(); // jne, sicepat, jnt
            $table->string('shipping_service')->nullable();
            $table->string('shipping_tracking_number')->nullable();
            $table->json('shipping_address'); // Snapshot recipient, phone, address, city, postal_code
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name');
            $table->string('variant_name')->nullable();
            $table->string('sku');
            $table->decimal('unit_price', 15, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 15, 2);
            $table->integer('weight_grams')->default(500);
            $table->timestamps();

            $table->index('order_id');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('payment_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->string('payment_method');
            $table->string('status')->default('pending'); // pending, success, failed, expired
            $table->timestamp('paid_at')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
