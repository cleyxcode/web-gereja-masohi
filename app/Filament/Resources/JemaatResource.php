<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JemaatResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use App\Notifications\AccountApprovedNotification;

class JemaatResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Jemaat';

    protected static ?string $pluralLabel = 'Jemaat';

    protected static ?string $modelLabel = 'Jemaat';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'jemaat');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan'  => 'Perempuan',
                            ])
                            ->native(false)
                            ->placeholder('Pilih jenis kelamin'),

                        Forms\Components\Select::make('is_approved')
                            ->label('Status Akun')
                            ->options([
                                '1' => '✅ Disetujui',
                                '0' => '❌ Ditolak',
                                ''  => '⏳ Menunggu',
                            ])
                            ->native(false)
                            ->placeholder('Menunggu persetujuan'),

                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->revealable()
                            ->helperText('Kosongkan jika tidak ingin mengubah password'),

                        Forms\Components\Hidden::make('role')
                            ->default('jemaat'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Data Keanggotaan')
                    ->schema([
                        Forms\Components\TextInput::make('sektor')
                            ->label('Sektor')
                            ->maxLength(100)
                            ->placeholder('Contoh: Sektor 1'),

                        Forms\Components\TextInput::make('unit')
                            ->label('Unit')
                            ->maxLength(100)
                            ->placeholder('Contoh: Pemuda'),

                        Forms\Components\TextInput::make('no_hp')
                            ->label('No. HP')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('08xxxxxxxxxx'),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),

                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->color(fn (?string $state): string => match($state) {
                        'Laki-laki' => 'info',
                        'Perempuan' => 'pink',
                        default     => 'gray',
                    })
                    ->placeholder('Belum diisi'),

                Tables\Columns\TextColumn::make('sektor')
                    ->label('Sektor')
                    ->searchable()
                    ->placeholder('Belum diisi')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('unit')
                    ->label('Unit')
                    ->searchable()
                    ->placeholder('Belum diisi')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('is_approved')
                    ->label('Status Akun')
                    ->formatStateUsing(fn ($state): string => match((string)$state) {
                        '1' => 'Disetujui',
                        '0' => 'Ditolak',
                        default => 'Menunggu',
                    })
                    ->badge()
                    ->color(fn ($state): string => match((string)$state) {
                        '1'  => 'success',
                        '0'  => 'danger',
                        default => 'warning',
                    })
                    ->icon(fn ($state): string => match((string)$state) {
                        '1'  => 'heroicon-o-check-circle',
                        '0'  => 'heroicon-o-x-circle',
                        default => 'heroicon-o-clock',
                    }),

                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->placeholder('Belum diisi')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),

                Tables\Filters\SelectFilter::make('is_approved')
                    ->label('Status Akun')
                    ->options([
                        '1' => '✅ Disetujui',
                        '0' => '❌ Ditolak',
                    ]),

                Tables\Filters\Filter::make('pending')
                    ->label('⏳ Menunggu Persetujuan')
                    ->query(fn (Builder $query): Builder => $query->whereNull('is_approved')),

                Tables\Filters\Filter::make('has_phone')
                    ->label('Punya No. HP')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('no_hp')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('setujui')
                        ->label('Setujui Akun')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Setujui Akun Jemaat')
                        ->modalDescription('Apakah Anda yakin ingin menyetujui akun ini? User akan mendapatkan email konfirmasi dan dapat login ke sistem.')
                        ->modalSubmitActionLabel('Ya, Setujui')
                        ->action(function (User $record) {
                            $record->update(['is_approved' => true]);
                            // Kirim email notifikasi jika disetujui
                            try {
                                $record->notify(new AccountApprovedNotification());
                            } catch (\Exception $e) {
                                // Ignore email error in local if SMTP not configured yet
                            }
                        })
                        ->visible(fn (User $record): bool => $record->is_approved !== true),

                    Tables\Actions\Action::make('tolak')
                        ->label('Tolak Akun')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak Akun Jemaat')
                        ->modalDescription('Apakah Anda yakin ingin menolak akun ini? User tidak akan dapat login ke sistem.')
                        ->modalSubmitActionLabel('Ya, Tolak')
                        ->action(fn (User $record) => $record->update(['is_approved' => false]))
                        ->visible(fn (User $record): bool => $record->is_approved !== false),

                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('view_pendaftaran')
                        ->label('Lihat Pendaftaran')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->color('info')
                        ->url(fn (User $record): string => route('filament.admin.resources.pendaftarans.index', [
                            'tableFilters' => [
                                'user_id' => [
                                    'value' => $record->id,
                                ],
                            ],
                        ]))
                        ->visible(fn (User $record): bool => $record->pendaftaran_count > 0),

                    Tables\Actions\Action::make('view_saran')
                        ->label('Lihat Saran')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->url(fn (User $record): string => route('filament.admin.resources.sarans.index', [
                            'tableFilters' => [
                                'user_id' => [
                                    'value' => $record->id,
                                ],
                            ],
                        ]))
                        ->visible(fn (User $record): bool => $record->saran_count > 0),

                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Jemaat')
                        ->modalDescription('Apakah Anda yakin ingin menghapus jemaat ini? Data pendaftaran dan saran terkait akan ikut terhapus.')
                        ->modalSubmitActionLabel('Ya, Hapus'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Jemaat Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus jemaat terpilih? Data pendaftaran dan saran terkait akan ikut terhapus.')
                        ->modalSubmitActionLabel('Ya, Hapus'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJemaats::route('/'),
            'create' => Pages\CreateJemaat::route('/create'),
            'edit' => Pages\EditJemaat::route('/{record}/edit'),
            'view' => Pages\ViewJemaat::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }
}