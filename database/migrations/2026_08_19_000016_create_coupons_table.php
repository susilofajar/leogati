<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode kupon promosi');
            $table->string('name')->comment('Nama / deskripsi promo');
            $table->enum('type', ['fixed', 'percent'])->comment('fixed = potongan rupiah, percent = persentase diskon');
            $table->decimal('value', 15, 2)->comment('Nilai potongan (rupiah atau angka persen)');
            $table->decimal('min_purchase_amount', 15, 2)->default(0)->comment('Batas minimum belanja');
            $table->decimal('max_discount_amount', 15, 2)->nullable()->comment('Maksimal nominal diskon untuk tipe persentase');
            $table->integer('usage_limit')->nullable()->comment('Batas total pemakaian kupon (null = tanpa batas)');
            $table->integer('used_count')->default(0)->comment('Jumlah pemakaian kupon saat ini');
            $table->dateTime('start_date')->nullable()->comment('Tanggal mulai berlaku');
            $table->dateTime('end_date')->nullable()->comment('Tanggal berakhir');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('code');
            $table->index('is_active');
        });

        // Tambah kolom coupon_id dan coupon_code ke tabel orders
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->after('discount_amount')->constrained('coupons')->onDelete('set null');
            $table->string('coupon_code')->nullable()->after('coupon_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['coupon_id', 'coupon_code']);
        });
        Schema::dropIfExists('coupons');
    }
};
