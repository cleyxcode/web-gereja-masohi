<?php

namespace Database\Seeders;

use App\Models\LaporanKeuangan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LaporanKeuanganSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Data laporan keuangan gereja format baru (per-periode)
        $laporan = [
            // ─── Saldo Murni ─────────────────────────────────────────────────
            [
                'judul'            => 'Saldo Murni',
                'kategori'         => 'murni',
                'urutan'           => 1,
                'periode_awal'     => '2025-03-22',
                'periode_akhir'    => '2025-03-29',
                'saldo_awal'       => 45476583,
                'total_penerimaan' => 36660500,
                'total_belanja'    => 9035000,
                'keterangan'       => 'Laporan keuangan minggu 22 - 29 Maret 2025.',
            ],
            [
                'judul'            => 'Saldo Murni',
                'kategori'         => 'murni',
                'urutan'           => 1,
                'periode_awal'     => '2025-04-05',
                'periode_akhir'    => '2025-04-12',
                'saldo_awal'       => 73102083,
                'total_penerimaan' => 41250000,
                'total_belanja'    => 12500000,
                'keterangan'       => 'Laporan keuangan minggu 5 - 12 April 2025.',
            ],
            [
                'judul'            => 'Saldo Murni',
                'kategori'         => 'murni',
                'urutan'           => 1,
                'periode_awal'     => '2025-04-13',
                'periode_akhir'    => '2025-04-19',
                'saldo_awal'       => 101852083,
                'total_penerimaan' => 38750000,
                'total_belanja'    => 8900000,
                'keterangan'       => 'Laporan keuangan minggu 13 - 19 April 2025.',
            ],

            // ─── Saldo UKP ───────────────────────────────────────────────────
            [
                'judul'            => 'Saldo UKP',
                'kategori'         => 'ukp',
                'urutan'           => 2,
                'periode_awal'     => '2025-03-15',
                'periode_akhir'    => '2025-03-22',
                'saldo_awal'       => 15792000,
                'total_penerimaan' => 2935000,
                'total_belanja'    => 0,
                'keterangan'       => 'Laporan UKP minggu 15 - 22 Maret 2025.',
            ],
            [
                'judul'            => 'Saldo UKP',
                'kategori'         => 'ukp',
                'urutan'           => 2,
                'periode_awal'     => '2025-04-05',
                'periode_akhir'    => '2025-04-12',
                'saldo_awal'       => 18727000,
                'total_penerimaan' => 3150000,
                'total_belanja'    => 500000,
                'keterangan'       => 'Laporan UKP minggu 5 - 12 April 2025.',
            ],

            // ─── Dana Khusus ─────────────────────────────────────────────────
            [
                'judul'            => 'Dana Renovasi Kapel',
                'kategori'         => 'khusus',
                'urutan'           => 3,
                'periode_awal'     => '2025-01-01',
                'periode_akhir'    => '2025-03-31',
                'saldo_awal'       => 25000000,
                'total_penerimaan' => 15000000,
                'total_belanja'    => 18000000,
                'keterangan'       => 'Dana khusus renovasi kapel dan ruang ibadah Kuartal I 2025.',
            ],
        ];

        foreach ($laporan as $data) {
            LaporanKeuangan::create(array_merge($data, [
                'created_by' => $admin->id,
            ]));
        }

        $this->command->info('✅ LaporanKeuanganSeeder: ' . count($laporan) . ' laporan berhasil di-seed.');
    }
}