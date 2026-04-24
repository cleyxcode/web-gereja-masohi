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
            $table->string('judul');                        // Contoh: "Saldo Murni", "Saldo UKP"
            $table->string('kategori')->default('murni');   // murni | ukp | khusus
            $table->date('periode_awal');                   // Tanggal awal saldo
            $table->date('periode_akhir');                  // Tanggal akhir periode
            $table->integer('urutan')->default(1);          // Urutan tampil (1, 2, ...)

            // Data keuangan
            $table->decimal('saldo_awal', 15, 2)->default(0);       // Saldo Per [tgl]
            $table->decimal('total_penerimaan', 15, 2)->default(0); // Penerimaan tanggal s/d
            $table->decimal('total_belanja', 15, 2)->default(0);    // Belanja Tanggal s/d

            // Kolom computed (optional, bisa dihitung di model)
            // jumlah_penerimaan = saldo_awal + total_penerimaan
            // saldo_akhir = jumlah_penerimaan - total_belanja

            // Catatan tambahan bebas (keterangan extra)
            $table->text('keterangan')->nullable();

            // Upload file laporan (opsional, menggantikan input manual)
            $table->string('file_laporan')->nullable(); // path ke PDF/Excel

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