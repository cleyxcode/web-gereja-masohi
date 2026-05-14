<?php

namespace App\Jobs;

use App\Mail\BeritaNotification;
use App\Models\Berita;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBeritaEmailJob
{
    use Dispatchable, Queueable, SerializesModels;

    public $berita;
    public $isUpdate;

    public function __construct(Berita $berita, bool $isUpdate = false)
    {
        $this->berita  = $berita;
        $this->isUpdate = $isUpdate;
    }

    public function handle(): void
    {
        $users = User::where('role', 'jemaat')->where('is_approved', true)->get();

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new BeritaNotification($this->berita, $this->isUpdate));
            } catch (\Exception $e) {
                Log::error("Gagal mengirim email berita ke {$user->email}: " . $e->getMessage());
            }
        }
    }
}

