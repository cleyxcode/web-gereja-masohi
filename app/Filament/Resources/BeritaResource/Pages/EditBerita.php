<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;


class EditBerita extends EditRecord
{
    protected static string $resource = BeritaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Buang kolom yang tidak ada di tabel
        unset($data['send_email_notification']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        // Baca langsung dari Livewire raw state
        $rawState = $this->form->getRawState();
        $sendEmail = (bool) ($rawState['send_email_notification'] ?? false);

        if ($sendEmail) {
            \App\Jobs\SendBeritaEmailJob::dispatch($this->record, true);

            Notification::make()
                ->title('Notifikasi Email Dijadwalkan')
                ->body('Email update berita sedang dikirim ke seluruh jemaat di background.')
                ->success()
                ->send();
        }
    }
}