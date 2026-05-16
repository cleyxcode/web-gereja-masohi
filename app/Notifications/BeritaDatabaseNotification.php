<?php

namespace App\Notifications;

use App\Models\Berita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BeritaDatabaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $berita;
    public $isUpdate;

    /**
     * Create a new notification instance.
     */
    public function __construct(Berita $berita, bool $isUpdate = false)
    {
        $this->berita = $berita;
        $this->isUpdate = $isUpdate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Hanya database, tidak pakai email
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $prefix = $this->isUpdate ? 'Update Berita:' : 'Berita Baru:';
        
        return [
            'berita_id' => $this->berita->id,
            'judul' => $prefix . ' ' . $this->berita->judul,
            'pesan' => 'Silakan cek pembaruan berita terbaru di website.',
            'url' => url('/berita/' . $this->berita->id), // Sesuaikan dengan route frontend berita jika ada
        ];
    }
}
