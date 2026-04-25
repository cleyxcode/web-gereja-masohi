<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_keuangan', function (Blueprint $table) {
            if (!Schema::hasColumn('laporan_keuangan', 'custom_fields')) {
                $table->json('custom_fields')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('laporan_keuangan', function (Blueprint $table) {
            $table->dropColumn('custom_fields');
        });
    }
};
