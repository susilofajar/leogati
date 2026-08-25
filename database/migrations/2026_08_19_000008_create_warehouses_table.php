<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Kode unik gudang, mis: GUD-PUSAT');
            $table->string('name')->comment('Nama gudang');
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('pic_name')->nullable()->comment('Person In Charge / Penanggung Jawab');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false)->comment('Gudang utama default');
            $table->timestamps();

            $table->index('is_active');
            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
