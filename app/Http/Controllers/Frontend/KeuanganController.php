<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LaporanKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanKeuanganExport;
use Maatwebsite\Excel\Facades\Excel;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk tabel/list
        $query = LaporanKeuangan::with('creator');

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Statistik Ringkasan
        // 1. Total Penerimaan & Belanja (Sum dari database)
        $totalPenerimaan = LaporanKeuangan::sum('total_penerimaan');
        $totalBelanja = LaporanKeuangan::sum('total_belanja');

        // 2. Total Saldo Akhir (Sum dari saldo akhir TERBARU tiap judul & kategori)
        // Kita ambil record terbaru (id terbesar) untuk setiap kombinasi judul/kategori
        $latestReports = LaporanKeuangan::whereIn('id', function($q) {
            $q->select(DB::raw('MAX(id)'))
              ->from('laporan_keuangan')
              ->groupBy('judul', 'kategori');
        })->get();

        $totalSaldoAkhir = $latestReports->sum(fn($l) => (float) $l->saldo_akhir);
        $terbilangTotal = LaporanKeuangan::terbilang((int) $totalSaldoAkhir) . ' Rupiah.';

        // Paginate list untuk view
        $laporanList = $query->orderBy('periode_akhir', 'desc')
            ->orderBy('urutan', 'asc')
            ->paginate(5);

        return view('frontend.keuangan.index', compact(
            'totalPenerimaan',
            'totalBelanja',
            'totalSaldoAkhir',
            'terbilangTotal',
            'laporanList'
        ));
    }

    public function export(Request $request)
    {
        $fileName = 'laporan-keuangan-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new LaporanKeuanganExport($request),
            $fileName
        );
    }
}