<?php

namespace App\Mail;

use App\Models\Saran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaranAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $saran;

    public function __construct(Saran $saran)
    {
        $this->saran = $saran;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Saran & Masukan Baru dari Jemaat',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.saran-admin-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
