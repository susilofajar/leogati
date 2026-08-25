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
        Schema::create('saved_pc_builds', function (Blueprint $table) {
            $table->id();
            $table->string('share_token', 20)->unique()->comment('Token unik publik untuk membagikan rakitan PC');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('build_name')->default('Simulasi Rakitan PC Gaming');
            $table->json('components')->comment('Array JSON daftar komponen yang dipilih');
            $table->decimal('total_price', 15, 2)->default(0);
            $table->unsignedInteger('estimated_wattage')->default(0)->comment('Estimasi total konsumsi daya (Watt)');
            $table->enum('compatibility_status', ['compatible', 'warning', 'incompatible'])->default('compatible');
            $table->json('compatibility_messages')->nullable()->comment('Daftar catatan dan peringatan kompatibilitas');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('share_token');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_pc_builds');
    }
};
