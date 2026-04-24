<?php

namespace App\Exports;

use App\Models\LaporanKeuangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanKeuanganExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnFormatting,
    ShouldAutoSize,
    WithEvents
{
    protected $request;
    protected $totalPenerimaan = 0;
    protected $totalBelanja = 0;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = LaporanKeuangan::with('creator')
            ->orderBy('periode_akhir', 'desc');

        if ($this->request->filled('kategori')) {
            $query->where('kategori', $this->request->kategori);
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        $this->totalPenerimaan = $data->sum('total_penerimaan');
        $this->totalBelanja = $data->sum('total_belanja');

        return $data;
    }

    public function headings(): array
    {
        return [
            'Judul Laporan',
            'Kategori',
            'Periode',
            'Saldo Awal',
            'Penerimaan',
            'Belanja',
            'Saldo Akhir',
            'Dibuat Oleh'
        ];
    }

    public function map($row): array
    {
        return [
            $row->judul,
            ucfirst($row->kategori),
            $row->periode_awal->format('d/m/Y') . ' - ' . $row->periode_akhir->format('d/m/Y'),
            $row->saldo_awal,
            $row->total_penerimaan,
            $row->total_belanja,
            $row->saldo_akhir,
            $row->creator->name ?? '-',
        ];
    }

    // FORMAT RUPIAH
    public function columnFormats(): array
    {
        return [
            'D' => '"Rp" #,##0_-',
            'E' => '"Rp" #,##0_-',
            'F' => '"Rp" #,##0_-',
            'G' => '"Rp" #,##0_-',
        ];
    }

    // STYLE HEADER
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ],
        ];
    }

    // TAMBAH STYLE & TOTAL DI BAWAH
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // PERLUAS KOLOM MANUAL
                $sheet->getColumnDimension('A')->setWidth(30);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(18);
                $sheet->getColumnDimension('E')->setWidth(18);
                $sheet->getColumnDimension('F')->setWidth(18);
                $sheet->getColumnDimension('G')->setWidth(18);
                $sheet->getColumnDimension('H')->setWidth(25);

                // BORDER SEMUA TABEL
                $sheet->getStyle('A1:H' . $lastRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // TAMBAH TOTAL
                $totalRow = $lastRow + 1;

                $sheet->setCellValue('C' . $totalRow, 'TOTAL');
                $sheet->setCellValue('E' . $totalRow, $this->totalPenerimaan);
                $sheet->setCellValue('F' . $totalRow, $this->totalBelanja);

                $sheet->getStyle('C' . $totalRow . ':G' . $totalRow)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN
                        ]
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8FAFC']
                    ]
                ]);

                // FORMAT RUPIAH UNTUK TOTAL
                $sheet->getStyle('E' . $totalRow . ':F' . $totalRow)
                    ->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0_-');
            },
        ];
    }
}