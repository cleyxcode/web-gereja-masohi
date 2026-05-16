<?php

namespace App\Filament\Resources\JadwalIbadahResource\Pages;

use App\Filament\Resources\JadwalIbadahResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateJadwalIbadah extends CreateRecord
{
    protected static string $resource = JadwalIbadahResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        unset($data['send_email_notification']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $rawState = $this->form->getRawState();
        $sendEmail = (bool) ($rawState['send_email_notification'] ?? false);

        if ($sendEmail) {
            \App\Jobs\SendJadwalEmailJob::dispatch($this->record, false);

            Notification::make()
                ->title('Notifikasi Email Dijadwalkan')
                ->body('Email jadwal ibadah sedang dikirim ke seluruh jemaat di background.')
                ->success()
                ->send();
        }
    }
}