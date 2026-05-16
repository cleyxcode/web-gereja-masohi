<?php

namespace App\Notifications;

use App\Models\JadwalIbadah;
use Illuminate\Notifications\Notification;

class JadwalDatabaseNotification extends Notification
{
    // Tidak menggunakan ShouldQueue agar langsung masuk ke DB tanpa perlu queue worker
    
    public function __construct(
        public JadwalIbadah $jadwal,
        public bool $isUpdate = false
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $tanggal = \Carbon\Carbon::parse($this->jadwal->tanggal)->translatedFormat('d F Y');
        $prefix  = $this->isUpdate ? 'Update Jadwal Ibadah:' : 'Jadwal Ibadah Baru:';

        return [
            'jadwal_id' => $this->jadwal->id,
            'judul'     => $prefix . ' ' . $tanggal,
            'pesan'     => "Jadwal ibadah hari {$tanggal} telah dipublikasikan. Tempat: {$this->jadwal->tempat}.",
            'url'       => url('/jadwal/' . $this->jadwal->id),
        ];
    }
}
