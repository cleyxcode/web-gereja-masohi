<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('name');
            $table->string('sektor')->nullable()->after('jenis_kelamin');
            $table->string('unit')->nullable()->after('sektor');
            // Status persetujuan akun: null = belum diproses, true = disetujui, false = ditolak
            $table->boolean('is_approved')->nullable()->default(null)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['jenis_kelamin', 'sektor', 'unit', 'is_approved']);
        });
    }
};
