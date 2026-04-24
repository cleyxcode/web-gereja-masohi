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

                        Forms\Components\Select::make('kategori')
                            ->label('Kategori')
                            ->options([
                                'murni'   => 'Saldo Murni',
                                'ukp'     => 'Saldo UKP',
                                'khusus'  => 'Dana Khusus',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('urutan')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->helperText('Angka kecil tampil lebih dulu (1, 2, 3...)'),

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
                            ->step(1000)
                            ->live(onBlur: true)
                            ->helperText(fn (Get $get) =>
                                'Saldo Per ' . ($get('periode_awal')
                                    ? \Carbon\Carbon::parse($get('periode_awal'))->translatedFormat('d F Y')
                                    : '...')
                            ),

                        Forms\Components\TextInput::make('total_penerimaan')
                            ->label('Total Penerimaan (Rp)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->step(1000)
                            ->live(onBlur: true)
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
                            ->step(1000)
                            ->live(onBlur: true)
                            ->helperText(fn (Get $get) =>
                                'Belanja ' . (($get('periode_awal') && $get('periode_akhir'))
                                    ? \Carbon\Carbon::parse($get('periode_awal'))->translatedFormat('d F Y') . ' s/d ' .
                                      \Carbon\Carbon::parse($get('periode_akhir'))->translatedFormat('d F Y')
                                    : '...')
                            ),

                        // Preview saldo akhir otomatis
                        Forms\Components\Placeholder::make('preview_saldo')
                            ->label('📊 Preview Saldo Akhir')
                            ->content(function (Get $get): string {
                                $saldoAwal       = (float) ($get('saldo_awal') ?? 0);
                                $penerimaan      = (float) ($get('total_penerimaan') ?? 0);
                                $belanja         = (float) ($get('total_belanja') ?? 0);
                                $jumlahPenerimaan = $saldoAwal + $penerimaan;
                                $saldoAkhir      = $jumlahPenerimaan - $belanja;

                                return 'Jumlah Penerimaan: Rp ' . number_format($jumlahPenerimaan, 0, ',', '.') .
                                    ' | Saldo Akhir: Rp ' . number_format($saldoAkhir, 0, ',', '.');
                            }),
                    ])
                    ->columns(2),

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
                Tables\Columns\TextColumn::make('urutan')
                    ->label('#')
                    ->sortable()
                    ->width(50),

                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('kategori')
                    ->label('Kategori')
                    ->colors([
                        'info'    => 'murni',
                        'warning' => 'ukp',
                        'success' => 'khusus',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'murni'  => 'Saldo Murni',
                        'ukp'    => 'Saldo UKP',
                        'khusus' => 'Dana Khusus',
                        default  => ucfirst($state),
                    }),

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
                Tables\Filters\SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'murni'  => 'Saldo Murni',
                        'ukp'    => 'Saldo UKP',
                        'khusus' => 'Dana Khusus',
                    ]),
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
            ->defaultSort('urutan', 'asc');
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