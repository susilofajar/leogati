<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Kode unik supplier, mis: SUP-0001');
            $table->string('name')->comment('Nama perusahaan supplier');
            $table->string('pic_name')->nullable()->comment('Person In Charge');
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('npwp', 30)->nullable()->comment('Nomor Pokok Wajib Pajak');
            $table->string('payment_terms', 100)->nullable()->comment('Syarat pembayaran, mis: NET30, COD');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
