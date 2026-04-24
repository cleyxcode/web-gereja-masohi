<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus tabel lama dan buat ulang dengan struktur baru
        Schema::dropIfExists('laporan_keuangan');

        Schema::create('laporan_keuangan', function (Blueprint $table) {
            $table->id();

            // Identitas laporan
            $table->string('judul');
            $table->string('kategori')->default('murni');   // bebas: murni, ukp, khusus, dll
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->integer('urutan')->default(1);
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->decimal('total_penerimaan', 15, 2)->default(0);
            $table->decimal('total_belanja', 15, 2)->default(0);
            $table->json('custom_fields')->nullable(); // baris tambahan bebas dari admin
            $table->text('keterangan')->nullable();
            $table->string('file_laporan')->nullable();

            // Metadata
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_keuangan');
    }
};