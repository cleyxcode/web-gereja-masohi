<?php

namespace App\Jobs;

use App\Models\Berita;
use App\Models\User;
use App\Notifications\BeritaDatabaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendBeritaEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Timeout per job: 60 detik
     */
    public int $timeout = 60;

    public int $tries = 2;

    public function __construct(
        public Berita $berita,
        public bool $isUpdate = false
    ) {}

    public function handle(): void
    {
        // Ambil semua jemaat
        $users = User::where('role', 'jemaat')
            ->where('is_approved', true)
            ->get();

        Log::info("[SendBeritaNotification] Mengirim notifikasi aplikasi ke {$users->count()} jemaat untuk berita ID {$this->berita->id}");

        // Mengirim notifikasi database ke semua jemaat sekaligus
        Notification::send($users, new BeritaDatabaseNotification($this->berita, $this->isUpdate));
    }
}
