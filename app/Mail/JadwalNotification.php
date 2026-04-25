<?php

namespace App\Mail;

use App\Models\JadwalIbadah;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JadwalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwal;
    public $isUpdate;

    public function __construct(JadwalIbadah $jadwal, bool $isUpdate = false)
    {
        $this->jadwal = $jadwal;
        $this->isUpdate = $isUpdate;
    }

    public function envelope(): Envelope
    {
        $subjectPrefix = $this->isUpdate ? 'Update Jadwal Ibadah' : 'Jadwal Ibadah Baru';
        return new Envelope(
            subject: $subjectPrefix,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.jadwal-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
