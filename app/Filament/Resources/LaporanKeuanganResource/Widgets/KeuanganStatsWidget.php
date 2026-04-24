<?php

namespace App\Filament\Resources\LaporanKeuanganResource\Widgets;

use App\Models\LaporanKeuangan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KeuanganStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $all = LaporanKeuangan::all();

        $totalPenerimaan = $all->sum(fn ($l) => (float) $l->total_penerimaan);
        $totalBelanja    = $all->sum(fn ($l) => (float) $l->total_belanja);
        $totalSaldoAkhir = $all->sum(fn ($l) => $l->saldo_akhir);

        return [
            Stat::make('Total Penerimaan', 'Rp ' . number_format($totalPenerimaan, 0, ',', '.'))
                ->description('Akumulasi semua penerimaan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Total Belanja', 'Rp ' . number_format($totalBelanja, 0, ',', '.'))
                ->description('Akumulasi semua belanja')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->chart([3, 5, 3, 6, 5, 4, 3, 7]),

            Stat::make('Total Saldo Akhir', 'Rp ' . number_format($totalSaldoAkhir, 0, ',', '.'))
                ->description($totalSaldoAkhir >= 0 ? 'Surplus' : 'Defisit')
                ->descriptionIcon($totalSaldoAkhir >= 0 ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                ->color($totalSaldoAkhir >= 0 ? 'success' : 'warning'),
        ];
    }
}