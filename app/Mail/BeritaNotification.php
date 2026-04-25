<?php

namespace App\Mail;

use App\Models\Berita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BeritaNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $berita;
    public $isUpdate;

    public function __construct(Berita $berita, bool $isUpdate = false)
    {
        $this->berita = $berita;
        $this->isUpdate = $isUpdate;
    }

    public function envelope(): Envelope
    {
        $subjectPrefix = $this->isUpdate ? 'Update Berita: ' : 'Berita Baru: ';
        return new Envelope(
            subject: $subjectPrefix . $this->berita->judul,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.berita-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
