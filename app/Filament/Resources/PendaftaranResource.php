<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Models\Pendaftaran;
use App\Notifications\AccountApprovedNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Pendaftaran';
    protected static ?string $pluralLabel = 'Pendaftaran';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Utama')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('Akun Jemaat')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\Select::make('jenis')
                        ->label('Jenis Layanan')
                        ->options([
                            'baptis' => 'Baptis Kudus',
                            'sidi'   => 'Sidi (Peneguhan)',
                            'nikah'  => 'Pemberkatan Nikah',
                        ])
                        ->required()
                        ->live()
                        ->native(false),

                    Forms\Components\TextInput::make('nama')
                        ->label(fn (Forms\Get $get) => $get('jenis') === 'baptis' ? 'Nama Anak' : 'Nama Lengkap')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\DatePicker::make('tanggal_daftar')
                        ->label('Tanggal Pelaksanaan / Pemberkatan')
                        ->required(fn (Forms\Get $get) => $get('jenis') !== 'sidi')
                        ->visible(fn (Forms\Get $get) => $get('jenis') !== 'sidi')
                        ->native(false)
                        ->displayFormat('d/m/Y'),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending'   => 'Pending',
                            'disetujui' => 'Disetujui',
                            'ditolak'   => 'Ditolak',
                        ])
                        ->default('pending')
                        ->required()
                        ->native(false)
                        ->hiddenOn('create'),

                    Forms\Components\Textarea::make('catatan')
                        ->label('Catatan Tambahan')
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),

            // === SECTION SIDI & BAPTIS ===
            Forms\Components\Section::make('Data Kelahiran')
                ->schema([
                    Forms\Components\TextInput::make('tempat_lahir')
                        ->label('Tempat Lahir')
                        ->required(fn (Forms\Get $get) => in_array($get('jenis'), ['sidi', 'baptis'])),
                    Forms\Components\DatePicker::make('tanggal_lahir')
                        ->label('Tanggal Lahir')
                        ->required(fn (Forms\Get $get) => in_array($get('jenis'), ['sidi', 'baptis']))
                        ->native(false)
                        ->displayFormat('d/m/Y'),
                ])
                ->columns(2)
                ->visible(fn (Forms\Get $get) => in_array($get('jenis'), ['sidi', 'baptis'])),

            // === SECTION SIDI ===
            Forms\Components\Section::make('Data Khusus Sidi')
                ->schema([
                    Forms\Components\Select::make('status_wasmi')
                        ->label('Status WASMI')
                        ->options([
                            'sudah' => 'Sudah Lulus',
                            'belum' => 'Belum Lulus',
                        ])
                        ->required(fn (Forms\Get $get) => $get('jenis') === 'sidi')
                        ->live()
                        ->native(false),
                    Forms\Components\TextInput::make('tahun_lulus_wasmi')
                        ->label('Tahun Lulus WASMI')
                        ->numeric()
                        ->visible(fn (Forms\Get $get) => $get('jenis') === 'sidi' && $get('status_wasmi') === 'sudah'),
                ])
                ->columns(2)
                ->visible(fn (Forms\Get $get) => $get('jenis') === 'sidi'),

            // === SECTION BAPTIS ===
            Forms\Components\Section::make('Data Khusus Baptis')
                ->schema([
                    Forms\Components\TextInput::make('nama_ayah')->label('Nama Ayah')->required(fn (Forms\Get $get) => $get('jenis') === 'baptis'),
                    Forms\Components\TextInput::make('nama_ibu')->label('Nama Ibu')->required(fn (Forms\Get $get) => $get('jenis') === 'baptis'),
                    Forms\Components\DatePicker::make('tanggal_nikah_ortu')->label('Tanggal Nikah Ortu')->required(fn (Forms\Get $get) => $get('jenis') === 'baptis')->native(false),
                    Forms\Components\TextInput::make('nama_saksi_1')->label('Nama Saksi 1')->required(fn (Forms\Get $get) => $get('jenis') === 'baptis'),
                    Forms\Components\TextInput::make('nama_saksi_2')->label('Nama Saksi 2')->nullable(),
                    Forms\Components\TextInput::make('asal_jemaat_sektor')->label('Asal Jemaat / Sektor')->required(fn (Forms\Get $get) => $get('jenis') === 'baptis'),
                    Forms\Components\TextInput::make('baptis_di_gereja')->label('Baptis di Gereja')->required(fn (Forms\Get $get) => $get('jenis') === 'baptis'),
                ])
                ->columns(2)
                ->visible(fn (Forms\Get $get) => $get('jenis') === 'baptis'),

            // === SECTION NIKAH - SUAMI ===
            Forms\Components\Section::make('Data Calon Suami')
                ->schema([
                    Forms\Components\TextInput::make('nama_suami')->label('Nama Suami')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                    Forms\Components\TextInput::make('tempat_lahir_suami')->label('Tempat Lahir')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                    Forms\Components\DatePicker::make('tanggal_lahir_suami')->label('Tanggal Lahir')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah')->native(false),
                    Forms\Components\TextInput::make('pekerjaan_suami')->label('Pekerjaan')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                    Forms\Components\TextInput::make('agama_suami')->label('Agama')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                    Forms\Components\Textarea::make('alamat_suami')->label('Alamat')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah')->columnSpanFull(),
                    
                    Forms\Components\Fieldset::make('Orang Tua Suami')
                        ->schema([
                            Forms\Components\TextInput::make('nama_ayah_suami')->label('Nama Ayah')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                            Forms\Components\TextInput::make('pekerjaan_ayah_suami')->label('Pekerjaan Ayah')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                            Forms\Components\Textarea::make('alamat_ayah_suami')->label('Alamat Ayah')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah')->columnSpanFull(),
                            
                            Forms\Components\TextInput::make('nama_ibu_suami')->label('Nama Ibu')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                            Forms\Components\TextInput::make('pekerjaan_ibu_suami')->label('Pekerjaan Ibu')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                            Forms\Components\Textarea::make('alamat_ibu_suami')->label('Alamat Ibu')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah')->columnSpanFull(),
                        ])->columns(2),
                ])
                ->columns(2)
                ->visible(fn (Forms\Get $get) => $get('jenis') === 'nikah'),

            // === SECTION NIKAH - ISTRI ===
            Forms\Components\Section::make('Data Calon Istri')
                ->schema([
                    Forms\Components\TextInput::make('nama_istri')->label('Nama Istri')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                    Forms\Components\TextInput::make('tempat_lahir_istri')->label('Tempat Lahir')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                    Forms\Components\DatePicker::make('tanggal_lahir_istri')->label('Tanggal Lahir')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah')->native(false),
                    Forms\Components\TextInput::make('pekerjaan_istri')->label('Pekerjaan')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                    Forms\Components\TextInput::make('agama_istri')->label('Agama')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                    Forms\Components\Textarea::make('alamat_istri')->label('Alamat')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah')->columnSpanFull(),
                    
                    Forms\Components\Fieldset::make('Orang Tua Istri')
                        ->schema([
                            Forms\Components\TextInput::make('nama_ayah_istri')->label('Nama Ayah')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                            Forms\Components\TextInput::make('pekerjaan_ayah_istri')->label('Pekerjaan Ayah')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                            Forms\Components\Textarea::make('alamat_ayah_istri')->label('Alamat Ayah')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah')->columnSpanFull(),
                            
                            Forms\Components\TextInput::make('nama_ibu_istri')->label('Nama Ibu')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                            Forms\Components\TextInput::make('pekerjaan_ibu_istri')->label('Pekerjaan Ibu')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah'),
                            Forms\Components\Textarea::make('alamat_ibu_istri')->label('Alamat Ibu')->required(fn (Forms\Get $get) => $get('jenis') === 'nikah')->columnSpanFull(),
                        ])->columns(2),
                ])
                ->columns(2)
                ->visible(fn (Forms\Get $get) => $get('jenis') === 'nikah'),

            // === SECTION DOKUMEN ===
            Forms\Components\Section::make('Foto & Dokumen')
                ->schema([
                    Forms\Components\FileUpload::make('foto')
                        ->label(fn (Forms\Get $get) => $get('jenis') === 'nikah' ? 'Pas Foto Gandeng (4x6)' : 'Foto Diri')
                        ->image()
                        ->disk('public')
                        ->directory('pendaftaran/foto')
                        ->nullable()
                        ->imagePreviewHeight('250')
                        ->downloadable()
                        ->openable()
                        ->columnSpanFull(),
                        
                    // Dokumen Sidi
                    Forms\Components\FileUpload::make('file_sertifikat_wasmi')
                        ->label('Sertifikat WASMI')
                        ->disk('public')
                        ->directory('pendaftaran/dokumen')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->visible(fn (Forms\Get $get) => $get('jenis') === 'sidi')
                        ->downloadable()
                        ->openable(),
                    Forms\Components\FileUpload::make('file_akta_kelahiran')
                        ->label('Akta Kelahiran')
                        ->disk('public')
                        ->directory('pendaftaran/dokumen')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->visible(fn (Forms\Get $get) => $get('jenis') === 'sidi')
                        ->downloadable()
                        ->openable(),

                    // Dokumen Nikah
                    Forms\Components\FileUpload::make('file_surat_pernyataan_ortu')
                        ->label('Surat Pernyataan Ortu')
                        ->disk('public')
                        ->directory('pendaftaran/dokumen')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->visible(fn (Forms\Get $get) => $get('jenis') === 'nikah')
                        ->downloadable()
                        ->openable(),
                    Forms\Components\FileUpload::make('file_surat_keterangan_lurah')
                        ->label('Surat Ket. Lurah (Belum Nikah)')
                        ->disk('public')
                        ->directory('pendaftaran/dokumen')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->visible(fn (Forms\Get $get) => $get('jenis') === 'nikah')
                        ->downloadable()
                        ->openable(),
                    Forms\Components\FileUpload::make('file_surat_pernyataan_mempelai')
                        ->label('Surat Pernyataan Mempelai')
                        ->disk('public')
                        ->directory('pendaftaran/dokumen')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->visible(fn (Forms\Get $get) => $get('jenis') === 'nikah')
                        ->downloadable()
                        ->openable(),
                    Forms\Components\FileUpload::make('file_ktp')
                        ->label('KTP')
                        ->disk('public')
                        ->directory('pendaftaran/dokumen')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->visible(fn (Forms\Get $get) => $get('jenis') === 'nikah')
                        ->downloadable()
                        ->openable(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Akun')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'baptis' => 'warning',
                        'sidi'   => 'info',
                        'nikah'  => 'pink',
                        default  => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'baptis' => 'Baptis Kudus',
                        'sidi'   => 'Sidi (Peneguhan)',
                        'nikah'  => 'Pemberkatan Nikah',
                        default  => ucfirst($state),
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_daftar')
                    ->label('Tgl Pelaksanaan')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->width(50)
                    ->height(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'pending'   => 'warning',
                        'disetujui' => 'success',
                        'ditolak'   => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')
                    ->options([
                        'baptis' => 'Baptis Kudus',
                        'sidi'   => 'Sidi (Peneguhan)',
                        'nikah'  => 'Pemberkatan Nikah',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'disetujui' => 'Disetujui',
                        'ditolak'   => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Setujui Pendaftaran')
                        ->modalDescription('Apakah Anda yakin ingin menyetujui pendaftaran ini? Email konfirmasi akan dikirim ke pengguna.')
                        ->visible(fn (Pendaftaran $record): bool => $record->status === 'pending')
                        ->action(function (Pendaftaran $record) {
                            $record->update(['status' => 'disetujui']);

                            // Aktifkan akun user
                            if ($record->user) {
                                $record->user->update(['is_approved' => true]);
                                // Kirim email notifikasi ke user
                                $record->user->notify(new AccountApprovedNotification());
                            }

                            Notification::make()->success()
                                ->title('Pendaftaran Disetujui')
                                ->body('Pendaftaran atas nama ' . $record->nama . ' telah disetujui dan email konfirmasi telah dikirim.')
                                ->send();
                        }),

                    Tables\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak Pendaftaran')
                        ->modalDescription('Apakah Anda yakin ingin menolak pendaftaran ini?')
                        ->visible(fn (Pendaftaran $record): bool => $record->status === 'pending')
                        ->action(function (Pendaftaran $record) {
                            $record->update(['status' => 'ditolak']);

                            // Tandai akun user sebagai ditolak
                            if ($record->user) {
                                $record->user->update(['is_approved' => false]);
                            }

                            Notification::make()->warning()
                                ->title('Pendaftaran Ditolak')
                                ->body('Pendaftaran atas nama ' . $record->nama . ' telah ditolak.')
                                ->send();
                        }),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('approve_bulk')
                        ->label('Setujui Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['status' => 'disetujui']);
                            Notification::make()->success()
                                ->title($records->count() . ' pendaftaran telah disetujui.')
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('reject_bulk')
                        ->label('Tolak Terpilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['status' => 'ditolak']);
                            Notification::make()->warning()
                                ->title($records->count() . ' pendaftaran telah ditolak.')
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'edit'   => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}