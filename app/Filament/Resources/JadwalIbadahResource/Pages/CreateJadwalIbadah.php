<?php

namespace App\Filament\Resources\JadwalIbadahResource\Pages;

use App\Filament\Resources\JadwalIbadahResource;
use App\Jobs\SendJadwalEmailJob;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateJadwalIbadah extends CreateRecord
{
    protected static string $resource = JadwalIbadahResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // Kirim notifikasi database ke seluruh jemaat secara otomatis
        SendJadwalEmailJob::dispatch($this->record, false);

        Notification::make()
            ->title('Notifikasi Terkirim!')
            ->body('Notifikasi jadwal ibadah baru sudah dikirim ke seluruh jemaat.')
            ->success()
            ->send();
    }
}