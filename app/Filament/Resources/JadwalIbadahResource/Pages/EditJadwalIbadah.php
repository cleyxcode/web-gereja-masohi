<?php

namespace App\Filament\Resources\JadwalIbadahResource\Pages;

use App\Filament\Resources\JadwalIbadahResource;
use Filament\Actions;
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        if ($this->data['send_email_notification'] ?? false) {
            \App\Jobs\SendJadwalEmailJob::dispatch($this->record, true);
        }
    }
}