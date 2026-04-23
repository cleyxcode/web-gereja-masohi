<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Ubah tanggal_daftar menjadi nullable karena untuk Sidi mungkin tidak diperlukan
            $table->date('tanggal_daftar')->nullable()->change();
            
            // Kolom khusus Sidi
            $table->string('tempat_lahir')->nullable()->after('nama');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->enum('status_wasmi', ['sudah', 'belum'])->nullable()->after('tanggal_lahir');
            $table->string('tahun_lulus_wasmi')->nullable()->after('status_wasmi');
            $table->string('file_sertifikat_wasmi')->nullable()->after('tahun_lulus_wasmi');
            $table->string('file_akta_kelahiran')->nullable()->after('file_sertifikat_wasmi');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->date('tanggal_daftar')->nullable(false)->change();
            $table->dropColumn([
                'tempat_lahir',
                'tanggal_lahir',
                'status_wasmi',
                'tahun_lulus_wasmi',
                'file_sertifikat_wasmi',
                'file_akta_kelahiran',
            ]);
        });
    }
};
