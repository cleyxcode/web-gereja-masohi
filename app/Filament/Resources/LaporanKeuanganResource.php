<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanKeuanganResource\Pages;
use App\Models\LaporanKeuangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LaporanKeuanganResource extends Resource
{
    protected static ?string $model = LaporanKeuangan::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static ?string $pluralLabel = 'Laporan Keuangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ── IDENTITAS LAPORAN ──────────────────────────────────────────
                Forms\Components\Section::make('Identitas Laporan')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Laporan')
                            ->placeholder('Contoh: Saldo Murni, Saldo UKP')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('kategori')
                            ->label('Kategori')
                            ->placeholder('Contoh: murni, ukp, khusus, diakonia...')
                            ->helperText('Saran: Saldo Murni, Saldo UKP, Dana Khusus — atau isi sendiri sesuai kebutuhan')
                            ->required()
                            ->datalist([
                                'Saldo Murni',
                                'Saldo UKP',
                                'Dana Khusus',
                                'Dana Diakonia',
                                'Dana Renovasi',
                                'Dana Natal',
                                'Dana Paskah',
                                'Dana Sosial',
                            ])
                            ->live()
                            ->afterStateUpdated(function (Get $get, Forms\Set $set, ?string $state) {
                                if ($state && !$get('saldo_awal')) {
                                    $lastLaporan = \App\Models\LaporanKeuangan::where('kategori', $state)
                                        ->orderBy('periode_akhir', 'desc')
                                        ->first();
                                    
                                    if ($lastLaporan) {
                                        $set('saldo_awal', $lastLaporan->saldo_akhir);
                                        $set('periode_awal', $lastLaporan->periode_akhir->addDay()->format('Y-m-d'));
                                    }
                                }
                            })
                            ->maxLength(100),

                        Forms\Components\DatePicker::make('periode_awal')
                            ->label('Tanggal Saldo Awal')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        Forms\Components\DatePicker::make('periode_akhir')
                            ->label('Tanggal Akhir Periode')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->after('periode_awal'),
                    ])
                    ->columns(2),

                // ── DATA KEUANGAN ──────────────────────────────────────────────
                Forms\Components\Section::make('Data Keuangan')
                    ->description('Masukkan data keuangan manual. Saldo akhir akan dihitung otomatis.')
                    ->schema([
                        Forms\Components\TextInput::make('saldo_awal')
                            ->label('Saldo Awal (Rp)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->step(1)
                            ->live(onBlur: true)
                            ->extraInputAttributes(['step' => '1'])
                            ->inputMode('numeric')
                            ->placeholder('0')
                            ->prefix('Rp')
                            ->helperText(fn (Get $get) =>
                                ($get('saldo_awal') ? 'Terbilang: ' . \App\Models\LaporanKeuangan::terbilang((int)$get('saldo_awal')) . ' Rupiah.' : '') . 
                                ' | Saldo Per ' . ($get('periode_awal')
                                    ? \Carbon\Carbon::parse($get('periode_awal'))->translatedFormat('d F Y')
                                    : '...')
                            ),

                        Forms\Components\TextInput::make('total_penerimaan')
                            ->label('Total Penerimaan (Rp)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->step(1)
                            ->live(onBlur: true)
                            ->extraInputAttributes(['step' => '1'])
                            ->inputMode('numeric')
                            ->placeholder('0')
                            ->helperText(fn (Get $get) =>
                                'Penerimaan ' . (($get('periode_awal') && $get('periode_akhir'))
                                    ? \Carbon\Carbon::parse($get('periode_awal'))->translatedFormat('d F Y') . ' s/d ' .
                                      \Carbon\Carbon::parse($get('periode_akhir'))->translatedFormat('d F Y')
                                    : '...')
                            ),

                        Forms\Components\TextInput::make('total_belanja')
                            ->label('Total Belanja (Rp)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->step(1)
                            ->live(onBlur: true)
                            ->extraInputAttributes(['step' => '1'])
                            ->inputMode('numeric')
                            ->placeholder('0')
                            ->helperText(fn (Get $get) =>
                                'Belanja ' . (($get('periode_awal') && $get('periode_akhir'))
                                    ? \Carbon\Carbon::parse($get('periode_awal'))->translatedFormat('d F Y') . ' s/d ' .
                                      \Carbon\Carbon::parse($get('periode_akhir'))->translatedFormat('d F Y')
                                    : '...')
                            ),

                        // Preview saldo akhir otomatis
                        Forms\Components\Placeholder::make('preview_saldo')
                            ->label('📊 Ringkasan Saldo')
                            ->content(function (Get $get): \Illuminate\Support\HtmlString {
                                $saldoAwal       = (float) ($get('saldo_awal') ?? 0);
                                $penerimaan      = (float) ($get('total_penerimaan') ?? 0);
                                $belanja         = (float) ($get('total_belanja') ?? 0);
                                
                                $jumlahPenerimaan = $saldoAwal + $penerimaan;
                                $saldoAkhir       = $jumlahPenerimaan - $belanja;

                                // Hitung custom fields
                                $customFields = $get('custom_fields') ?? [];
                                foreach ($customFields as $field) {
                                    $jumlah = (float) ($field['jumlah'] ?? 0);
                                    $tipe   = $field['tipe'] ?? 'tambah';
                                    $saldoAkhir = ($tipe === 'kurang') ? $saldoAkhir - $jumlah : $saldoAkhir + $jumlah;
                                }

                                return new \Illuminate\Support\HtmlString("
                                    <div class='p-4 border rounded-lg bg-gray-50 dark:bg-gray-800 space-y-2'>
                                        <div class='flex justify-between'>
                                            <span class='text-gray-600 dark:text-gray-400'>Total Penerimaan (Masuk):</span>
                                            <span class='font-semibold text-success-600'>Rp " . number_format($jumlahPenerimaan, 0, ',', '.') . "</span>
                                        </div>
                                        <div class='flex justify-between'>
                                            <span class='text-gray-600 dark:text-gray-400'>Total Belanja (Keluar):</span>
                                            <span class='font-semibold text-danger-600'>Rp " . number_format($belanja, 0, ',', '.') . "</span>
                                        </div>
                                        <div class='flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700'>
                                            <span class='font-bold text-gray-900 dark:text-white'>SALDO AKHIR:</span>
                                            <span class='font-black text-primary-600 text-lg'>Rp " . number_format($saldoAkhir, 0, ',', '.') . "</span>
                                        </div>
                                    </div>
                                ");
                            }),
                    ])
                    ->columns(2),

                // ── BARIS KUSTOM TAMBAHAN ──────────────────────────────────────────
                Forms\Components\Section::make('Baris Data Tambahan (Opsional)')
                    ->description('Tambahkan baris data kustom sesuai kebutuhan — akan tampil di laporan setelah data utama.')
                    ->schema([
                        Forms\Components\Repeater::make('custom_fields')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('Keterangan Baris')
                                    ->placeholder('Contoh: Penerimaan Persembahan Syukur')
                                    ->required()
                                    ->columnSpan(2),

                                Forms\Components\Select::make('tipe')
                                    ->label('Jenis')
                                    ->options([
                                        'tambah' => '(+) Menambah Saldo',
                                        'kurang' => '(-) Mengurangi Saldo',
                                    ])
                                    ->default('tambah')
                                    ->required()
                                    ->native(false)
                                    ->live(),

                                Forms\Components\TextInput::make('jumlah')
                                    ->label('Jumlah (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->minValue(0)
                                    ->step(1)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->prefix('Rp'),
                            ])
                            ->columns(4)
                            ->addActionLabel('+ Tambah Baris')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                ($state['label'] ?? 'Baris Baru') . ' — Rp ' .
                                number_format((float)($state['jumlah'] ?? 0), 0, ',', '.')
                            ),
                    ])
                    ->collapsible(),

                // ── KETERANGAN & FILE ──────────────────────────────────────────
                Forms\Components\Section::make('Keterangan & File Pendukung')
                    ->schema([
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Tambahan')
                            ->placeholder('Catatan, informasi tambahan, atau rincian laporan...')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('file_laporan')
                            ->label('Upload File Laporan (Opsional)')
                            ->helperText('Upload PDF atau Excel jika ada. Jika diisi, file ini akan tampil sebagai lampiran di halaman keuangan jemaat.')
                            ->disk('public')
                            ->directory('laporan-keuangan')
                            ->acceptedFileTypes(['application/pdf', 'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                    ]),

                // Hidden: created_by
                Forms\Components\Hidden::make('created_by')
                    ->default(fn () => Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                Tables\Columns\TextColumn::make('periode_awal')
                    ->label('Periode Awal')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('periode_akhir')
                    ->label('Periode Akhir')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('saldo_awal')
                    ->label('Saldo Awal')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_penerimaan')
                    ->label('Penerimaan')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_belanja')
                    ->label('Belanja')
                    ->money('IDR')
                    ->color('danger')
                    ->sortable(),

                Tables\Columns\IconColumn::make('file_laporan')
                    ->label('File')
                    ->boolean()
                    ->trueIcon('heroicon-o-paper-clip')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('kategori')
                    ->form([
                        Forms\Components\TextInput::make('kategori')
                            ->label('Filter Kategori')
                            ->placeholder('Contoh: murni, ukp, diakonia...'),
                    ])
                    ->query(fn ($query, array $data) =>
                        $data['kategori']
                            ? $query->where('kategori', 'like', '%' . $data['kategori'] . '%')
                            : $query
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('periode_akhir', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLaporanKeuangans::route('/'),
            'create' => Pages\CreateLaporanKeuangan::route('/create'),
            'edit'   => Pages\EditLaporanKeuangan::route('/{record}/edit'),
        ];
    }
}