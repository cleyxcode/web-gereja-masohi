<?php

namespace App\Notifications;

use App\Models\Pendaftaran;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PendaftaranMasukNotification extends Notification
{
    public function __construct(
        protected Pendaftaran $pendaftaran
    ) {}

    /**
     * Channel pengiriman: database (Filament in-app) & mail (Gmail).
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Email yang dikirim ke admin via Gmail.
     */
    public function toMail(object $notifiable): MailMessage
    {
        [$jenis, $nama] = $this->getJenisNama();

        return (new MailMessage)
            ->subject("📋 Pendaftaran {$jenis} Baru — {$nama}")
            ->view('emails.pendaftaran-masuk', [
                'pendaftaran' => $this->pendaftaran,
                'jenis'       => $jenis,
                'nama'        => $nama,
            ]);
    }

    /**
     * Data notifikasi in-app untuk Filament database notifications.
     * Format array ini kompatibel dengan Filament v3 DatabaseNotifications.
     */
    public function toDatabase(object $notifiable): array
    {
        [$jenis, $nama] = $this->getJenisNama();

        $icon = match ($this->pendaftaran->jenis) {
            'baptis' => '💧',
            'sidi'   => '🙏',
            'nikah'  => '💍',
            default  => '📋',
        };

        $color = match ($this->pendaftaran->jenis) {
            'baptis' => 'info',
            'sidi'   => 'warning',
            'nikah'  => 'danger',
            default  => 'warning',
        };

        return [
            'title'  => "{$icon} Pendaftaran {$jenis} Baru",
            'body'   => "Atas nama **{$nama}** telah mengajukan pendaftaran {$jenis}. Mohon segera ditinjau.",
            'color'  => $color,
            'icon'   => 'heroicon-o-clipboard-document-list',
            'status' => 'warning',
            'actions' => [
                [
                    'name'  => 'lihat',
                    'label' => 'Lihat Pendaftaran',
                    'url'   => route('filament.admin.resources.pendaftarans.index'),
                    'shouldMarkAsRead' => true,
                ],
            ],
            'pendaftaran_id' => $this->pendaftaran->id,
            'jenis'          => $this->pendaftaran->jenis,
        ];
    }

    /**
     * Helper: kembalikan [label jenis, nama pendaftar]
     */
    private function getJenisNama(): array
    {
        $jenis = match ($this->pendaftaran->jenis) {
            'baptis' => 'Baptis Kudus',
            'sidi'   => 'Sidi (Peneguhan)',
            'nikah'  => 'Pemberkatan Nikah',
            default  => ucfirst($this->pendaftaran->jenis),
        };

        $nama = $this->pendaftaran->nama;

        if (! $nama && $this->pendaftaran->jenis === 'nikah') {
            $nama = ($this->pendaftaran->nama_suami ?? '?')
                . ' & '
                . ($this->pendaftaran->nama_istri ?? '?');
        }

        return [$jenis, $nama ?? 'Tidak diketahui'];
    }
}
