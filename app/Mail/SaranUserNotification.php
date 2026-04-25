<?php

namespace App\Mail;

use App\Models\Saran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaranUserNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $saran;

    public function __construct(Saran $saran)
    {
        $this->saran = $saran;
    }

    public function envelope(): Envelope
    {
        $statusText = $this->saran->status === 'ditindaklanjuti' ? 'Telah Ditindaklanjuti' : 'Telah Dibaca';
        return new Envelope(
            subject: 'Saran Anda ' . $statusText,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.saran-user-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
