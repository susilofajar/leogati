<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->tinyInteger('rating')->unsigned()->comment('Bintang rating 1 s/d 5');
            $table->string('title')->nullable()->comment('Judul ulasan singkat');
            $table->text('comment')->comment('Isi ulasan pengalaman belanja');
            $table->boolean('is_verified_purchase')->default(true)->comment('Pembeli terverifikasi dari data transaksi');
            $table->boolean('is_approved')->default(true)->comment('Status moderasi admin');
            $table->text('admin_reply')->nullable()->comment('Tanggapan resmi dari penjual/admin');
            $table->dateTime('admin_replied_at')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'is_approved']);
            $table->index('user_id');
            $table->unique(['product_id', 'user_id', 'order_id'], 'unique_product_user_order_review');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
