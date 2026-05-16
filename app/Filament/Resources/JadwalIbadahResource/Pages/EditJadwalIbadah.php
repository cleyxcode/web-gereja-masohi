<?php

namespace App\Filament\Resources\JadwalIbadahResource\Pages;

use App\Filament\Resources\JadwalIbadahResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditJadwalIbadah extends EditRecord
{
    protected static string $resource = JadwalIbadahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['send_email_notification']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $rawState = $this->form->getRawState();
        $sendEmail = (bool) ($rawState['send_email_notification'] ?? false);

        if ($sendEmail) {
            \App\Jobs\SendJadwalEmailJob::dispatch($this->record, true);

            Notification::make()
                ->title('Notifikasi Email Dijadwalkan')
                ->body('Email update jadwal ibadah sedang dikirim ke seluruh jemaat di background.')
                ->success()
                ->send();
        }
    }
}