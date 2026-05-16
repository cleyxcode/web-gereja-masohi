<?php

namespace App\Notifications;

use App\Models\Berita;
use Illuminate\Notifications\Notification;

class BeritaDatabaseNotification extends Notification
{
    // Tidak menggunakan ShouldQueue agar langsung masuk ke DB tanpa perlu queue worker
    
    public function __construct(
        public Berita $berita,
        public bool $isUpdate = false
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $prefix = $this->isUpdate ? 'Update Berita:' : 'Berita Baru:';

        return [
            'berita_id' => $this->berita->id,
            'judul'     => $prefix . ' ' . $this->berita->judul,
            'pesan'     => 'Silakan cek berita terbaru di website.',
            'url'       => url('/berita/' . $this->berita->id),
        ];
    }
}
