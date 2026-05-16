<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use App\Jobs\SendBeritaEmailJob;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateBerita extends CreateRecord
{
    protected static string $resource = BeritaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // Kirim notifikasi database ke seluruh jemaat secara otomatis
        SendBeritaEmailJob::dispatch($this->record, false);

        Notification::make()
            ->title('Notifikasi Terkirim!')
            ->body('Notifikasi berita baru sudah dikirim ke seluruh jemaat.')
            ->success()
            ->send();
    }
}