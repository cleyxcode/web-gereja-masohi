<?php

namespace App\Filament\Widgets;

use App\Models\LaporanKeuangan;
use Filament\Widgets\ChartWidget;

class KeuanganChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Keuangan (Per Periode Laporan)';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Ambil 10 laporan terbaru, urut dari terlama ke terbaru
        $laporan = LaporanKeuangan::orderBy('periode_akhir', 'asc')
            ->latest('id')
            ->take(10)
            ->get()
            ->reverse()
            ->values();

        $labels      = [];
        $penerimaan  = [];
        $belanja     = [];
        $saldoAkhir  = [];

        foreach ($laporan as $l) {
            $labels[]     = $l->judul . ' (' . $l->periode_akhir->format('d/m/Y') . ')';
            $penerimaan[] = (float) $l->total_penerimaan;
            $belanja[]    = (float) $l->total_belanja;
            $saldoAkhir[] = $l->saldo_akhir;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Penerimaan',
                    'data'            => $penerimaan,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor'     => 'rgb(34, 197, 94)',
                    'borderWidth'     => 2,
                    'fill'            => true,
                ],
                [
                    'label'           => 'Belanja',
                    'data'            => $belanja,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'borderColor'     => 'rgb(239, 68, 68)',
                    'borderWidth'     => 2,
                    'fill'            => true,
                ],
                [
                    'label'           => 'Saldo Akhir',
                    'data'            => $saldoAkhir,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor'     => 'rgb(59, 130, 246)',
                    'borderWidth'     => 2,
                    'fill'            => false,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'top'],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
                'x' => [
                    'ticks' => ['maxRotation' => 45],
                ],
            ],
        ];
    }
}