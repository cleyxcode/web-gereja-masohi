<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LaporanKeuangan extends Model
{
    protected $table = 'laporan_keuangan';

    protected $fillable = [
        'judul',
        'kategori',
        'periode_awal',
        'periode_akhir',
        'urutan',
        'saldo_awal',
        'total_penerimaan',
        'total_belanja',
        'custom_fields',
        'keterangan',
        'file_laporan',
        'created_by',
    ];

    protected $casts = [
        'periode_awal'      => 'date',
        'periode_akhir'     => 'date',
        'saldo_awal'        => 'decimal:2',
        'total_penerimaan'  => 'decimal:2',
        'total_belanja'     => 'decimal:2',
        'custom_fields'     => 'array',
    ];

    // ─── Computed Attributes ────────────────────────────────────────────────

    /**
     * Jumlah Penerimaan = Saldo Awal + Total Penerimaan
     */
    public function getJumlahPenerimaanAttribute(): float
    {
        return (float) $this->saldo_awal + (float) $this->total_penerimaan;
    }

    /**
     * Saldo Akhir = Jumlah Penerimaan - Total Belanja +/- custom fields
     */
    public function getSaldoAkhirAttribute(): float
    {
        $saldo = $this->jumlah_penerimaan - (float) $this->total_belanja;

        // Hitung custom fields tambahan
        if (!empty($this->custom_fields)) {
            foreach ($this->custom_fields as $field) {
                $jumlah = (float) ($field['jumlah'] ?? 0);
                $tipe   = $field['tipe'] ?? 'tambah';
                $saldo  = $tipe === 'kurang' ? $saldo - $jumlah : $saldo + $jumlah;
            }
        }

        return $saldo;
    }

    /**
     * URL file laporan jika ada
     */
    public function getFileLaporanUrlAttribute(): ?string
    {
        return $this->file_laporan
            ? Storage::disk('public')->url($this->file_laporan)
            : null;
    }

    /**
     * Terbilang dalam Bahasa Indonesia (konversi angka ke kata)
     */
    public function getTerbilangAttribute(): string
    {
        return self::terbilang((int) $this->saldo_akhir) . ' Rupiah.';
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Konversi angka ke terbilang Indonesia (sampai ratusan triliun)
     */
    public static function terbilang(int $angka): string
    {
        if ($angka === 0) {
            return 'Nol';
        }

        $angka = abs($angka);
        $huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        if ($angka < 12) {
            return $huruf[$angka];
        } elseif ($angka < 20) {
            return self::terbilang($angka - 10) . ' Belas';
        } elseif ($angka < 100) {
            return self::terbilang((int)($angka / 10)) . ' Puluh ' . self::terbilang($angka % 10);
        } elseif ($angka < 200) {
            return 'Seratus ' . self::terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return self::terbilang((int)($angka / 100)) . ' Ratus ' . self::terbilang($angka % 100);
        } elseif ($angka < 2000) {
            return 'Seribu ' . self::terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            return self::terbilang((int)($angka / 1000)) . ' Ribu ' . self::terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            return self::terbilang((int)($angka / 1000000)) . ' Juta ' . self::terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            return self::terbilang((int)($angka / 1000000000)) . ' Miliar ' . self::terbilang($angka % 1000000000);
        } elseif ($angka < 1000000000000000) {
            return self::terbilang((int)($angka / 1000000000000)) . ' Triliun ' . self::terbilang($angka % 1000000000000);
        }

        return '';
    }
}