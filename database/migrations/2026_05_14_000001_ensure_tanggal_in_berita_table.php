<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migration ini memastikan kolom 'tanggal' ada di tabel berita.
     * Aman dijalankan meski kolom sudah ada (menggunakan hasColumn check).
     */
    public function up(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            if (!Schema::hasColumn('berita', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('gambar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            if (Schema::hasColumn('berita', 'tanggal')) {
                $table->dropColumn('tanggal');
            }
        });
    }
};
