<?php

namespace App\Jobs;

use App\Mail\SaranUserNotification;
use App\Models\Saran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSaranUserEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $saran;

    public function __construct(Saran $saran)
    {
        $this->saran = $saran;
    }

    public function handle(): void
    {
        if ($this->saran->user && $this->saran->user->email) {
            try {
                Mail::to($this->saran->user->email)->send(new SaranUserNotification($this->saran));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Gagal mengirim email balasan saran ke {$this->saran->user->email}: " . $e->getMessage());
            }
        }
    }
}
