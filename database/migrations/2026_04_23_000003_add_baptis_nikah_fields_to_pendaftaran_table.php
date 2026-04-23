<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Fields for Baptis
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->date('tanggal_nikah_ortu')->nullable();
            $table->string('nama_saksi_1')->nullable();
            $table->string('nama_saksi_2')->nullable();
            $table->string('asal_jemaat_sektor')->nullable();
            $table->string('baptis_di_gereja')->nullable();

            // Fields for Nikah - Suami
            $table->string('nama_suami')->nullable();
            $table->string('tempat_lahir_suami')->nullable();
            $table->date('tanggal_lahir_suami')->nullable();
            $table->text('alamat_suami')->nullable();
            $table->string('pekerjaan_suami')->nullable();
            $table->string('agama_suami')->nullable();
            
            // Fields for Nikah - Ortu Suami
            $table->string('nama_ayah_suami')->nullable();
            $table->string('pekerjaan_ayah_suami')->nullable();
            $table->text('alamat_ayah_suami')->nullable();
            $table->string('nama_ibu_suami')->nullable();
            $table->string('pekerjaan_ibu_suami')->nullable();
            $table->text('alamat_ibu_suami')->nullable();

            // Fields for Nikah - Istri
            $table->string('nama_istri')->nullable();
            $table->string('tempat_lahir_istri')->nullable();
            $table->date('tanggal_lahir_istri')->nullable();
            $table->text('alamat_istri')->nullable();
            $table->string('pekerjaan_istri')->nullable();
            $table->string('agama_istri')->nullable();
            
            // Fields for Nikah - Ortu Istri
            $table->string('nama_ayah_istri')->nullable();
            $table->string('pekerjaan_ayah_istri')->nullable();
            $table->text('alamat_ayah_istri')->nullable();
            $table->string('nama_ibu_istri')->nullable();
            $table->string('pekerjaan_ibu_istri')->nullable();
            $table->text('alamat_ibu_istri')->nullable();

            // Uploads for Nikah
            $table->string('file_surat_pernyataan_ortu')->nullable();
            $table->string('file_surat_keterangan_lurah')->nullable();
            $table->string('file_surat_pernyataan_mempelai')->nullable();
            $table->string('file_ktp')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn([
                'nama_ayah', 'nama_ibu', 'tanggal_nikah_ortu', 'nama_saksi_1', 'nama_saksi_2', 'asal_jemaat_sektor', 'baptis_di_gereja',
                'nama_suami', 'tempat_lahir_suami', 'tanggal_lahir_suami', 'alamat_suami', 'pekerjaan_suami', 'agama_suami',
                'nama_ayah_suami', 'pekerjaan_ayah_suami', 'alamat_ayah_suami', 'nama_ibu_suami', 'pekerjaan_ibu_suami', 'alamat_ibu_suami',
                'nama_istri', 'tempat_lahir_istri', 'tanggal_lahir_istri', 'alamat_istri', 'pekerjaan_istri', 'agama_istri',
                'nama_ayah_istri', 'pekerjaan_ayah_istri', 'alamat_ayah_istri', 'nama_ibu_istri', 'pekerjaan_ibu_istri', 'alamat_ibu_istri',
                'file_surat_pernyataan_ortu', 'file_surat_keterangan_lurah', 'file_surat_pernyataan_mempelai', 'file_ktp'
            ]);
        });
    }
};
