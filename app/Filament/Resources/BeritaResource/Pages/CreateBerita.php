<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;


class CreateBerita extends CreateRecord
{
    protected static string $resource = BeritaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set created_by dan buang kolom yang tidak ada di tabel
        $data['created_by'] = auth()->id();
        unset($data['send_email_notification']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // Baca langsung dari Livewire raw state (bukan dari $data yang sudah dimutasi)
        $rawState = $this->form->getRawState();
        $sendEmail = (bool) ($rawState['send_email_notification'] ?? false);

        if ($sendEmail) {
            \App\Jobs\SendBeritaEmailJob::dispatch($this->record, false);

            Notification::make()
                ->title('Notifikasi Email Dijadwalkan')
                ->body('Email berita sedang dikirim ke seluruh jemaat di background.')
                ->success()
                ->send();
        }
    }
}