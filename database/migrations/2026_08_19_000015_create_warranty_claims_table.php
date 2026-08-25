<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_number')->unique()->comment('Format: WC-YYYYMMDD-XXXX');

            $table->foreignId('serial_number_id')
                  ->constrained('serial_numbers')
                  ->onDelete('cascade');
            $table->foreignId('customer_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->foreignId('order_id')
                  ->nullable()
                  ->constrained('orders')
                  ->onDelete('set null');

            $table->enum('issue_category', [
                'dead_on_arrival',   // Mati total saat diterima
                'defective',         // Cacat produksi / komponen rusak
                'malfunction',       // Kerusakan fungsi setelah pemakaian normal
                'physical_damage',   // Kerusakan fisik (terjatuh, terbentur, dll)
                'other',             // Lainnya
            ]);
            $table->text('issue_description')->comment('Deskripsi masalah dari pelanggan');

            $table->enum('status', [
                'submitted',   // Baru diajukan
                'reviewing',   // Sedang ditinjau admin
                'approved',    // Disetujui, menunggu pengiriman unit
                'in_repair',   // Sedang diperbaiki di service center
                'repaired',    // Selesai diperbaiki
                'replaced',    // Diganti unit baru
                'rejected',    // Ditolak (tidak termasuk cakupan garansi)
                'closed',      // Selesai / ditutup
            ])->default('submitted');

            $table->text('admin_notes')->nullable()->comment('Catatan internal admin/teknisi');
            $table->text('resolution')->nullable()->comment('Resolusi akhir klaim garansi');

            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('customer_id');
            $table->index('serial_number_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_claims');
    }
};
